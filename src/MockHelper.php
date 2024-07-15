<?php

namespace Webus;

use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MockHelper
{
    public static function mockPdoForDefault(TestCase $testCase, string $sql, array $sqlParams): MockObject
    {
        $statement = self::mockPDOStatementExecute($testCase, $sqlParams);

        return self::mockPdoForPrepare($testCase, $statement, $sql);
    }

    public static function mockPdoForFetch(TestCase $testCase, string $sql, array $sqlParams, $returnValue): MockObject
    {
        $statement = self::mockPDOStatementExecute($testCase, $sqlParams);
        $statement->expects($testCase->once())
            ->method('fetch')
            ->willReturn($returnValue);

        return self::mockPdoForPrepare($testCase, $statement, $sql);
    }

    public static function mockPDOForFetchAll(TestCase $testCase, $sql, $returnValue): MockObject
    {
        $mockStmt = self::mockPDOStatement($testCase);
        $mockStmt->expects($testCase->once())
            ->method('fetchAll')
            ->willReturn($returnValue);

        return self::mockPdoForQuery($testCase, $sql, $mockStmt);
    }

    public static function mockPdoForQuery(TestCase $testCase, string $sql, PDOStatement $returnPDOStatement): MockObject
    {
        $mockPdo = $testCase->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockPdo->expects($testCase->once())
            ->method('query')
            ->with($sql)
            ->willReturn($returnPDOStatement);

        return $mockPdo;
    }

    public static function mockPdoForLastInsertId(TestCase $testCase, string $sql, array $sqlParams, string|false $lastInsertId): MockObject
    {
        $statement = self::mockPDOStatementExecute($testCase, $sqlParams);

        $mockPdo = self::mockPdoForPrepare($testCase, $statement, $sql);
        $mockPdo->expects($testCase->once())
            ->method('lastInsertId')
            ->willReturn($lastInsertId);

        return $mockPdo;
    }


    /************ PRIVATE *******************/

    private static function mockPdoForPrepare(TestCase $testCase, $statement, $args = null): MockObject
    {
        $mockPdo = $testCase->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($args === null) {
            $mockPdo->expects($testCase->once())
                ->method('prepare')
                ->willReturn($statement);
        } else {
            $mockPdo->expects($testCase->once())
                ->method('prepare')
                ->with($args)
                ->willReturn($statement);
        }

        return $mockPdo;
    }

    private static function mockPDOStatementExecute(TestCase $testCase, array|null $sqlParams, bool $returnValue = true): MockObject
    {
        $statement = self::mockPDOStatement($testCase);

        if ($sqlParams === null) {
            $statement->expects($testCase->once())
                ->method('execute')
                ->willReturn($returnValue);
        } else {
            $statement->expects($testCase->once())
                ->method('execute')
                ->with($sqlParams)
                ->willReturn($returnValue);
        }

        return $statement;
    }

    private static function mockPDOStatement(TestCase $testCase): MockObject
    {
        return $testCase->getMockBuilder(PDOStatement::class)
            ->getMock();
    }
}
