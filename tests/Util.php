<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 01-08-18
 * Time: 17:16
 */

namespace test\edwrodrig\ncbi;


use edwrodrig\ncbi\taxonomy\builder\Builder;
use edwrodrig\ncbi\taxonomy\builder\Reader;

class Util
{
    /**
     * @param $data
     * @return string
     */
    static public function createNamesString(array $data) : string {

        $strings = [];
        foreach ( $data as $id => $node) {
            $name = $node['name'];

            $strings[] = "$id\t|\t$name\t|\t\t|\tscientific name\t|";
        }
        return implode("\n", $strings);
    }

    /**
     * @param $data
     * @return string
     */
    static public function createNodesString(array $data) {
        $strings = [];
        foreach ( $data as $id => $node ) {
            $parent_id = $node['parent_id'];
            $strings[] = "$id\t|\t$parent_id\t|";
        }
        return implode("\n", $strings);

    }

    static public function createFiles(string $folder, array $data) {
        mkdir($folder);
        file_put_contents($folder . '/nodes.dmp', self::createNodesString($data));
        file_put_contents($folder . '/names.dmp', self::createNamesString($data));

    }

    static public function createTempDatabase(string $target, array $data) {
        $file = tempnam(sys_get_temp_dir(), 'TEST_SQL');
        unlink($file);
        Util::createFiles($file, $data);
        $reader = new Reader($file);
        $builder = new Builder($reader);
        $builder->setTargetFilename($target);
        $builder->build();
    }
}