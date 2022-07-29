<?php

namespace App\Console\Commands\Dev;

use App\Console\Commands\Dev\Indexs\ProjectIndex;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Exception;
use Http\Promise\Promise;
use Illuminate\Console\Command;


class ESMigrate extends Command
{

    protected $signature = 'es:migrate';
    protected $description = 'Elasticsearch 索引结构迁移';

    protected mixed $es;
    protected Elasticsearch|Promise $res;

    public function handle()
    {
        $this->es = app('es');
        // 索引类数组，先留空
        $indices = [ProjectIndex::class];
        // 遍历索引类数组
        foreach ($indices as $indexClass) {
            // 调用类数组的 getAliasName() 方法来获取索引别名
            $aliasName = $indexClass::getAliasName();

            $this->info('正在处理索引 ' . $aliasName);

            // 通过 exists 方法判断这个别名是否存在
            if (!$this->es->indices()->exists(['index' => $aliasName])->asBool()) {
                $this->info('索引不存在，准备创建');
                $this->res = $this->createIndex($aliasName, $indexClass);
                if ($this->res->asBool()) {
                    $this->info('创建成功，准备初始化数据');
                }
                $indexClass::rebuild($aliasName);
                $this->info('操作成功');
                continue;
            }
            // 如果索引已经存在，那么尝试更新索引，如果更新失败会抛出异常
            try {
                $this->info('索引存在，准备更新');
                $this->updateIndex($aliasName, $indexClass);
            } catch (Exception) {
                $this->warn('更新失败，准备重建');
                $this->reCreateIndex($aliasName, $indexClass);
            }
            $this->info($aliasName . ' 操作成功');
        }
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/28 10:01
     * @Description: 创建新索引
     * @param $aliasName
     * @param $indexClass
     * @return Elasticsearch|Promise
     */
    protected function createIndex($aliasName, $indexClass): Elasticsearch|Promise
    {
        // 调用 create() 方法创建索引
        return $this->es->indices()->create([
            // 第一个版本的索引名后缀为 _0
            'index' => $aliasName . '_0',
            'body' => [
                // 调用索引类的 getSettings() 方法获取索引设置
                'settings' => $indexClass::getSettings(),
                'mappings' => [
                    // 调用索引类的 getProperties() 方法获取索引字段
                    'properties' => $indexClass::getProperties(),
                ],
                'aliases' => [
                    // 同时创建别名
                    $aliasName => new \stdClass(),
                ],
            ],
        ]);
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/28 10:00
     * @Description: 更新已有索引
     * @param $aliasName
     * @param $indexClass
     * @return void
     */
    protected function updateIndex($aliasName, $indexClass): void
    {

        // 暂时关闭索引
        $this->res = $this->es->indices()->close(['index' => $aliasName]);
        if ($this->res->asBool()) {
            $this->info('关闭索引' . $aliasName . ',ok');
        }
        // 更新索引设置
        $this->res = $this->es->indices()->putSettings([
            'index' => $aliasName,
            'body' => $indexClass::getSettings(),
        ]);
        if ($this->res->asBool()) {
            $this->info('更新索引设置,ok!');
        }
        // 更新索引字段
        $this->res = $this->es->indices()->putMapping([
            'index' => $aliasName,
            'body' => [
                '_source' => [
                    'enabled' => true
                ],
                //只能新增字段，不能修改原有字段属性
                'properties' => $indexClass::getProperties(),
            ],
        ]);
        if ($this->res->asBool()) {
            $this->info('更新索引字段,ok!');
        }
        // 重新打开索引
        $this->res = $this->es->indices()->open(['index' => $aliasName]);
        if ($this->res->asBool()) {
            $this->info('重新打开索引' . $aliasName . ',ok!');
        }
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/28 10:00
     * @Description:
     * @param $aliasName
     * @param $indexClass
     * @return void
     */
    protected function reCreateIndex($aliasName, $indexClass): void
    {
        // 获取索引信息，返回结构的 key 为索引名称，value 为别名
        $this->res = $this->es->indices()->getAlias(['index' => $aliasName]);
        if (!$this->res->asBool()) {
            $msg = '索引别名没有获取到:' . $aliasName;
            $this->error($msg);
            return;
        }
        $indexInfo = $this->res->asArray();
        // 取出第一个 key 即为索引名称
        $oldIndexName = array_keys($indexInfo)[0];
        // 用正则判断索引名称是否以 _数字 结尾
        if (!preg_match('~_(\d+)$~', $oldIndexName, $m)) {
            $msg = '索引名称不正确:' . $oldIndexName;
            $this->error($msg);
            return;
        }
        // 新的索引名称
        $newIndexName = $aliasName . '_' . ($m[1] + 1);
        $this->info('正在创建索引' . $newIndexName);
        $this->res = $this->es->indices()->create([
            'index' => $newIndexName,
            'body' => [
                'settings' => $indexClass::getSettings(),
                'mappings' => [
                    'properties' => $indexClass::getProperties(),
                ],
            ],
        ]);
        if ($this->res->asBool()) {
            $this->info('创建成功，准备重建数据');
        }
        $indexClass::rebuild($newIndexName);
        $this->info('重建成功，准备修改别名');
        $this->res = $this->es->indices()->putAlias(['index' => $newIndexName, 'name' => $aliasName]);
        if ($this->res->asBool()) {
            $this->info('修改成功，准备删除旧索引');
        }
        $this->res = $this->es->indices()->delete(['index' => $oldIndexName]);
        if ($this->res->asBool()) {
            $this->info('删除成功');
        }
    }
}
