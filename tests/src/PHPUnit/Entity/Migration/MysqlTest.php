<?php

namespace Yaoi\Database\Tests\PHPUnit\Entity\Migration;


use Yaoi\Database\Tests\Helper\Entity\OneABBR;
use Yaoi\Log;

class MysqlTest extends BaseTest
{
    protected $expectedMigrationLog = <<<EOD
Table creation expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires migration
CREATE TABLE `yaoi_database_tests_helper_entity_user` (
 `id` int NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`)
);
# OK
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
Table revision increased, added age, hostId
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires migration
ALTER TABLE `yaoi_database_tests_helper_entity_user`
ADD COLUMN `age` int DEFAULT NULL,
ADD COLUMN `host_id` int NOT NULL DEFAULT '0',
ADD INDEX `key_age` (`age`);
# Dependent tables found: yaoi_tests_host
# Apply, table yaoi_tests_host (Yaoi\Database\Tests\Helper\Entity\Host) is up to date
ALTER TABLE `yaoi_database_tests_helper_entity_user`
ADD CONSTRAINT `k47c117bc52f0210fe108cc481854b833` FOREIGN KEY (`host_id`) REFERENCES `yaoi_tests_host` (`id`);
# OK
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
Table revision increased, removed hostId, name, added sessionId, firstName, lastName
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires migration
ALTER TABLE `yaoi_database_tests_helper_entity_user`
ADD COLUMN `session_id` int NOT NULL DEFAULT '0',
ADD COLUMN `first_name` varchar(255) NOT NULL DEFAULT '',
ADD COLUMN `last_name` varchar(255) NOT NULL DEFAULT '',
DROP COLUMN `name`,
DROP COLUMN `host_id`,
ADD UNIQUE INDEX `unique_last_name_first_name` (`last_name`, `first_name`),
DROP INDEX `k47c117bc52f0210fe108cc481854b833`,
DROP FOREIGN KEY `k47c117bc52f0210fe108cc481854b833`;
# Dependent tables found: yaoi_tests_session
# Apply, table yaoi_tests_session (Yaoi\Database\Tests\Helper\Entity\Session) is up to date
ALTER TABLE `yaoi_database_tests_helper_entity_user`
ADD CONSTRAINT `kafec223e64a3bb12508718274f678a92` FOREIGN KEY (`session_id`) REFERENCES `yaoi_tests_session` (`id`);
# OK
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
Table removal expected
# Rollback, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires deletion
DROP TABLE `yaoi_database_tests_helper_entity_user`;
# OK
No action (is already non-existent) expected
# Rollback, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is already non-existent

EOD;


    public function testDefaultAlter()
    {
        $logString = '';
        $log = Log::getInstance(function()use(&$logString){
            $settings = new Log\Settings();
            $settings->driverClassName = Log\Driver\StringVar::className();
            $settings->storage = &$logString;
            return $settings;
        });

        OneABBR::migration()->rollback();
        $this->database->query(<<<SQL
CREATE TABLE `yaoi_database_tests_helper_entity_one_abbr` (
 `id` int NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 `address` varchar(255),
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY `unique_name` (`name`),
 PRIMARY KEY (`id`)
)

SQL
        )->execute();

        OneABBR::migration()->setLog($log)->apply();
        $this->assertSame(<<<LOG
# Apply, table yaoi_database_tests_helper_entity_one_abbr (Yaoi\Database\Tests\Helper\Entity\OneABBR) requires migration
ALTER TABLE `yaoi_database_tests_helper_entity_one_abbr`
MODIFY COLUMN `name` varchar(255) NOT NULL DEFAULT '';
# OK

LOG
            , $logString
        );
    }

}