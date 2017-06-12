<?php

namespace Yaoi\Database\Tests\PHPUnit\Entity;

class MysqliTest extends \Yaoi\Database\Tests\PHPUnit\Entity\TestBase
{

    protected $entityOneCreateTableExpected = <<<SQL
CREATE TABLE `yaoi_database_tests_helper_entity_one_abbr` (
 `id` int NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL DEFAULT '',
 `address` varchar(255) DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY `unique_name` (`name`),
 PRIMARY KEY (`id`)
)
SQL;

    protected $entityTwoCreateTableExpected = "CREATE TABLE `custom_name` (
 `id` int NOT NULL AUTO_INCREMENT,
 `one_id` int NOT NULL DEFAULT '0',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `info` varchar(255) DEFAULT NULL,
 KEY `key_info` (`info`),
 CONSTRAINT `k834443c31b77b69a3e4e39d60ef3a955` FOREIGN KEY (`one_id`) REFERENCES `yaoi_database_tests_helper_entity_one_abbr` (`id`),
 PRIMARY KEY (`id`)
)";

    public function setUp() {
        $this->database = \Yaoi\Database\Tests\Helper\CheckAvailable::getMysqli();
        parent::setUp();
    }

    protected $expectedMigrateLog = <<<EOD
# Rollback, table yaoi_database_tests_entity_session (Yaoi\Database\Tests\Helper\Entity\Session) is already non-existent
# Rollback, table yaoi_database_tests_entity_host (Yaoi\Database\Tests\Helper\Entity\Host) is already non-existent
# Rollback, table yaoi_database_tests_entity_tag (Yaoi\Database\Tests\Helper\Entity\Tag) is already non-existent
# Rollback, table yaoi_database_tests_entity_session_tag (Yaoi\Database\Tests\Helper\Entity\SessionTag) is already non-existent
# Apply, table yaoi_database_tests_entity_session (Yaoi\Database\Tests\Helper\Entity\Session) requires migration
CREATE TABLE `yaoi_database_tests_entity_session` (
 `id` int NOT NULL AUTO_INCREMENT,
 `host_id` int NOT NULL DEFAULT '0',
 `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `ended_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
);
# Dependent tables found: yaoi_database_tests_entity_host
# Apply, table yaoi_database_tests_entity_host (Yaoi\Database\Tests\Helper\Entity\Host) requires migration
CREATE TABLE `yaoi_database_tests_entity_host` (
 `id` int NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL DEFAULT '',
 UNIQUE KEY `unique_name` (`name`),
 PRIMARY KEY (`id`)
);
# OK
ALTER TABLE `yaoi_database_tests_entity_session`
ADD CONSTRAINT `fk_yaoi_database_tests_entity_session_host_id_yaoi_database_tests_entity_host_id` FOREIGN KEY (`host_id`) REFERENCES `yaoi_database_tests_entity_host` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;
# OK
# Apply, table yaoi_database_tests_entity_host (Yaoi\Database\Tests\Helper\Entity\Host) is up to date
# Apply, table yaoi_database_tests_entity_tag (Yaoi\Database\Tests\Helper\Entity\Tag) requires migration
CREATE TABLE `yaoi_database_tests_entity_tag` (
 `id` int NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL DEFAULT '',
 UNIQUE KEY `unique_name` (`name`),
 PRIMARY KEY (`id`)
);
# OK
# Apply, table yaoi_database_tests_entity_session_tag (Yaoi\Database\Tests\Helper\Entity\SessionTag) requires migration
CREATE TABLE `yaoi_database_tests_entity_session_tag` (
 `session_id` int NOT NULL DEFAULT '0',
 `tag_id` int NOT NULL DEFAULT '0',
 `added_at_ut` int NOT NULL DEFAULT '0',
 PRIMARY KEY (`session_id`, `tag_id`)
);
# Dependent tables found: yaoi_database_tests_entity_session, yaoi_database_tests_entity_tag
# Apply, table yaoi_database_tests_entity_session (Yaoi\Database\Tests\Helper\Entity\Session) is up to date
# Apply, table yaoi_database_tests_entity_tag (Yaoi\Database\Tests\Helper\Entity\Tag) is up to date
ALTER TABLE `yaoi_database_tests_entity_session_tag`;
# OK
# Apply, table yaoi_database_tests_entity_session (Yaoi\Database\Tests\Helper\Entity\Session) is up to date
# Apply, table yaoi_database_tests_entity_host (Yaoi\Database\Tests\Helper\Entity\Host) is up to date
# Apply, table yaoi_database_tests_entity_tag (Yaoi\Database\Tests\Helper\Entity\Tag) is up to date
# Apply, table yaoi_database_tests_entity_session_tag (Yaoi\Database\Tests\Helper\Entity\SessionTag) is up to date
# Rollback, table yaoi_database_tests_entity_session (Yaoi\Database\Tests\Helper\Entity\Session) requires deletion
ALTER TABLE `yaoi_database_tests_entity_session`
DROP FOREIGN KEY `fk_yaoi_database_tests_entity_session_host_id_yaoi_database_tests_entity_host_id`;
# Dependent tables found: yaoi_database_tests_entity_session_tag
# Rollback, table yaoi_database_tests_entity_session_tag (Yaoi\Database\Tests\Helper\Entity\SessionTag) requires deletion
DROP TABLE `yaoi_database_tests_entity_session_tag`;
# OK
DROP TABLE `yaoi_database_tests_entity_session`;
# OK
# Rollback, table yaoi_database_tests_entity_host (Yaoi\Database\Tests\Helper\Entity\Host) requires deletion
# Dependent tables found: yaoi_database_tests_entity_session
# Rollback, table yaoi_database_tests_entity_session (Yaoi\Database\Tests\Helper\Entity\Session) is already non-existent
DROP TABLE `yaoi_database_tests_entity_host`;
# OK
# Rollback, table yaoi_database_tests_entity_tag (Yaoi\Database\Tests\Helper\Entity\Tag) requires deletion
# Dependent tables found: yaoi_database_tests_entity_session_tag
# Rollback, table yaoi_database_tests_entity_session_tag (Yaoi\Database\Tests\Helper\Entity\SessionTag) is already non-existent
DROP TABLE `yaoi_database_tests_entity_tag`;
# OK
# Rollback, table yaoi_database_tests_entity_session_tag (Yaoi\Database\Tests\Helper\Entity\SessionTag) is already non-existent
# Rollback, table yaoi_database_tests_entity_session (Yaoi\Database\Tests\Helper\Entity\Session) is already non-existent
# Rollback, table yaoi_database_tests_entity_host (Yaoi\Database\Tests\Helper\Entity\Host) is already non-existent
# Rollback, table yaoi_database_tests_entity_tag (Yaoi\Database\Tests\Helper\Entity\Tag) is already non-existent
# Rollback, table yaoi_database_tests_entity_session_tag (Yaoi\Database\Tests\Helper\Entity\SessionTag) is already non-existent

EOD;


}