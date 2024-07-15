<?php

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webus\MockHelper;

class MockHelperTest extends TestCase
{
    public function testCreated()
    {
        $this->assertInstanceOf(MockHelper::class, new MockHelper());
    }

    public function testMockPdoForDefault()
    {
        // given
        $name = 'NAME';

        $sql = "INSERT INTO users SET name = ?";
        $sqlParams = [$name];

        // when
        $mockPdo = MockHelper::mockPdoForDefault($this, $sql, $sqlParams);

        $stmt = $mockPdo->prepare($sql);
        $stmt->execute($sqlParams);

        // then
        $this->assertInstanceOf(MockObject::class, $mockPdo);
    }

    public function testMockPdoForFetch()
    {
        // given
        $mockUser = [
            'id' => 1,
            'name' => 'dev',
        ];

        $sql = "SELECT * FROM users WHERE id = ?";
        $sqlParams = ['id' => 1];

        // when
        $mockPdo = MockHelper::mockPdoForFetch($this, $sql, $sqlParams, $mockUser);

        $stmt = $mockPdo->prepare($sql);
        $stmt->execute($sqlParams);
        $result = $stmt->fetch();

        // then
        $this->assertInstanceOf(MockObject::class, $mockPdo);
        $this->assertEquals($result, $mockUser);
    }

    public function testMockPdoForFetchAll()
    {
        // given
        $mockUserList = [
            [
                'id' => 1,
                'name' => 'dev',
            ],
        ];

        $sql = "SELECT * FROM users";

        // when
        $mockPdo = MockHelper::mockPDOForFetchAll($this, $sql, $mockUserList);

        $stmt = $mockPdo->query($sql);
        $result = $stmt->fetchAll();

        // then
        $this->assertInstanceOf(MockObject::class, $mockPdo);
        $this->assertEquals($result, $mockUserList);
    }

    public function testMockPdoForLastInsertId()
    {
        // given
        $lastInsertId = '7';
        $name = 'DEV';

        $sql = "INSERT INTO users SET name = ?";
        $sqlParams = [$name];

        // when
        $mockPdo = MockHelper::mockPdoForLastInsertId($this, $sql, $sqlParams, $lastInsertId);

        $stmt = $mockPdo->prepare($sql);
        $stmt->execute($sqlParams);
        $result = $mockPdo->lastInsertId();

        // then
        $this->assertInstanceOf(MockObject::class, $mockPdo);
        $this->assertEquals($result, $lastInsertId);
    }


}
