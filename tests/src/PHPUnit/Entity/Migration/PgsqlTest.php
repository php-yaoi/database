<?php

namespace Yaoi\Database\Tests\PHPUnit\Entity\Migration;


use Yaoi\Database;
use Yaoi\Database\Tests\Helper\CheckAvailable;
use Yaoi\Database\Tests\Helper\Entity\Host;
use Yaoi\Database\Tests\Helper\Entity\Session;
use Yaoi\Database\Tests\Helper\Entity\User;
use Yaoi\Log;

class PgsqlTest extends BaseTest
{

    public function setUp()
    {
        $this->database = CheckAvailable::getPgsql();
    }

    protected $expectedMigrationLog = <<<LOG
Table creation expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires migration
CREATE TABLE "yaoi_database_tests_helper_entity_user" (
 "id" SERIAL,
 "name" varchar(255) NOT NULL,
 PRIMARY KEY ("id")
);
# OK
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
Table revision increased, added age, hostId
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires migration
ALTER TABLE "yaoi_database_tests_helper_entity_user"
ADD COLUMN "age" int,
ADD COLUMN "host_id" int NOT NULL;
CREATE INDEX "key_age" ON "yaoi_database_tests_helper_entity_user" ("age");
# Dependent tables found: yaoi_tests_host
# Apply, table yaoi_tests_host (Yaoi\Database\Tests\Helper\Entity\Host) is up to date
ALTER TABLE "yaoi_database_tests_helper_entity_user"
ADD CONSTRAINT "k47c117bc52f0210fe108cc481854b833" FOREIGN KEY ("host_id") REFERENCES "yaoi_tests_host" ("id");
# OK
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
Table revision increased, removed hostId, name, added sessionId, firstName, lastName
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires migration
ALTER TABLE "yaoi_database_tests_helper_entity_user"
ADD COLUMN "session_id" int NOT NULL,
ADD COLUMN "first_name" varchar(255) NOT NULL,
ADD COLUMN "last_name" varchar(255) NOT NULL,
DROP COLUMN "name",
DROP COLUMN "host_id",
DROP CONSTRAINT IF EXISTS "k47c117bc52f0210fe108cc481854b833";
CREATE UNIQUE INDEX "unique_last_name_first_name" ON "yaoi_database_tests_helper_entity_user" ("last_name", "first_name");
# Dependent tables found: yaoi_tests_session
# Apply, table yaoi_tests_session (Yaoi\Database\Tests\Helper\Entity\Session) is up to date
ALTER TABLE "yaoi_database_tests_helper_entity_user"
ADD CONSTRAINT "kafec223e64a3bb12508718274f678a92" FOREIGN KEY ("session_id") REFERENCES "yaoi_tests_session" ("id");
# OK
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
Table removal expected
# Rollback, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires deletion
DROP TABLE "yaoi_database_tests_helper_entity_user";
# OK
No action (is already non-existent) expected
# Rollback, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is already non-existent

LOG;

    public function testUpdateSchema2()
    {
        $logString = '';
        $log = Log::getInstance(function () use (&$logString) {
            $settings = new Log\Settings();
            $settings->driverClassName = Log\Driver\StringVar::className();
            $settings->storage = &$logString;
            return $settings;
        });

        User::$revision = 2;
        User::bindDatabase($this->database, true);
        Host::bindDatabase($this->database, true);
        Session::bindDatabase($this->database, true);

        //$this->database->log(new Log('colored-stdout'));

        // prepare dependencies
        User::table()->migration()->rollback();
        Host::table()->migration()->apply();
        Session::table()->migration()->apply();

        Database\Entity\Migration::$enableStateCache = false;

        $log->push('Table revision increased, added age, hostId');
        User::$revision = 2;
        User::bindDatabase($this->database, true);
        User::table()->migration()->setLog($log)->apply();

        $log->push('No action (up to date) expected');
        User::bindDatabase($this->database, true);
        User::table()->migration()->setLog($log)->apply();

        $this->assertSame('Table revision increased, added age, hostId
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires migration
CREATE TABLE "yaoi_database_tests_helper_entity_user" (
 "id" SERIAL,
 "name" varchar(255) NOT NULL,
 "age" int,
 "host_id" int NOT NULL,
 PRIMARY KEY ("id")
);
CREATE INDEX "key_age" ON "yaoi_database_tests_helper_entity_user" ("age");
# Dependent tables found: yaoi_tests_host
# Apply, table yaoi_tests_host (Yaoi\Database\Tests\Helper\Entity\Host) is up to date
ALTER TABLE "yaoi_database_tests_helper_entity_user"
ADD CONSTRAINT "k47c117bc52f0210fe108cc481854b833" FOREIGN KEY ("host_id") REFERENCES "yaoi_tests_host" ("id");
# OK
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
', $logString);

    }


    public function testUpdateSchema3()
    {
        $logString = '';
        $log = Log::getInstance(function () use (&$logString) {
            $settings = new Log\Settings();
            $settings->driverClassName = Log\Driver\StringVar::className();
            $settings->storage = &$logString;
            return $settings;
        });

        User::$revision = 3;
        User::bindDatabase($this->database, true);
        Host::bindDatabase($this->database, true);
        Session::bindDatabase($this->database, true);

        //$this->database->log(new Log('colored-stdout'));

        // prepare dependencies
        User::table()->migration()->rollback();
        Host::table()->migration()->apply();
        Session::table()->migration()->apply();

        Database\Entity\Migration::$enableStateCache = false;

        $log->push('Table creation expected');
        User::$revision = 3;
        User::bindDatabase($this->database, true);
        User::table()->migration()->setLog($log)->apply();

        $log->push('No action (up to date) expected');
        User::bindDatabase($this->database, true);
        User::table()->migration()->setLog($log)->apply();

        $this->assertSame('Table creation expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) requires migration
CREATE TABLE "yaoi_database_tests_helper_entity_user" (
 "id" SERIAL,
 "age" int,
 "session_id" int NOT NULL,
 "first_name" varchar(255) NOT NULL,
 "last_name" varchar(255) NOT NULL,
 CONSTRAINT "unique_last_name_first_name" UNIQUE ("last_name", "first_name"),
 PRIMARY KEY ("id")
);
CREATE INDEX "key_age" ON "yaoi_database_tests_helper_entity_user" ("age");
# Dependent tables found: yaoi_tests_session
# Apply, table yaoi_tests_session (Yaoi\Database\Tests\Helper\Entity\Session) is up to date
ALTER TABLE "yaoi_database_tests_helper_entity_user"
ADD CONSTRAINT "kafec223e64a3bb12508718274f678a92" FOREIGN KEY ("session_id") REFERENCES "yaoi_tests_session" ("id");
# OK
No action (up to date) expected
# Apply, table yaoi_database_tests_helper_entity_user (Yaoi\Database\Tests\Helper\Entity\User) is up to date
', $logString);

    }


}