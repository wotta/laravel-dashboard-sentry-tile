<?php

namespace Wotta\SentryTile\Tests\Objects;

use Illuminate\Support\Facades\File;
use Wotta\SentryTile\Models\Issue;
use Wotta\SentryTile\Tests\TestCase;

class IssueObjectTest extends TestCase
{
    public function test_object_can_be_filled_from_model(): void
    {
        dd(File::files(__DIR__ . '/../../vendor/orchestra/testbench-core/laravel/database/migrations'));

        $issue = Issue::factory()->create();

        $this->assertInstanceOf(Issue::class, $issue);

        $this->assertSame($issue->title, (new \Wotta\SentryTile\Objects\Issue($issue))->title());
    }

    public function test_object_has_meta_title(): void
    {
        $issue = Issue::factory()->create([
            'meta' => ['title' => 'This is the meta title'],
        ]);

        $this->assertInstanceOf(Issue::class, $issue);

        $issueObject = (new \Wotta\SentryTile\Objects\Issue($issue));

        $this->assertIsObject($issueObject->meta());

        /** @var \ReflectionObject $reflectionObject */
        $reflectionObject = new \ReflectionObject($issueObject->meta());

        /** @var \ReflectionProperty $attributeProperty */
        $attributeProperty = tap($reflectionObject->getProperty('attributes'), fn (\ReflectionProperty $property) => $property->setAccessible(true));

        $this->assertArrayHasKey('title', $attributeProperty->getValue($issueObject->meta()));
        $this->assertSame($issue->meta['title'], $attributeProperty->getValue($issueObject->meta())['title']);
    }

    public function test_object_has_assigned_to_object(): void
    {
        $issue = Issue::factory()->create([
            'assigned_to' => [
                'id' => 123456,
                'name' => 'John Doe',
                'type' => 'user',
                'email' => 'johndoe@example.com',
            ],
        ]);

        $this->assertInstanceOf(Issue::class, $issue);

        $issueObject = (new \Wotta\SentryTile\Objects\Issue($issue));

        $this->assertIsObject($issueObject->assignedTo());

        $issueObject = (new \Wotta\SentryTile\Objects\Issue($issue));

        $this->assertIsObject($issueObject->assignedTo());

        /** @var \ReflectionObject $reflectionObject */
        $reflectionObject = new \ReflectionObject($issueObject->assignedTo());

        /** @var \ReflectionProperty $attributeProperty */
        $attributeProperty = tap($reflectionObject->getProperty('attributes'), fn (\ReflectionProperty $property) => $property->setAccessible(true));

        $this->assertArrayHasKey('id', $attributeProperty->getValue($issueObject->assignedTo()));
        $this->assertArrayHasKey('name', $attributeProperty->getValue($issueObject->assignedTo()));
        $this->assertArrayHasKey('type', $attributeProperty->getValue($issueObject->assignedTo()));
        $this->assertArrayHasKey('email', $attributeProperty->getValue($issueObject->assignedTo()));

        $this->assertSame($issue->assigned_to['id'], $attributeProperty->getValue($issueObject->assignedTo())['id']);
        $this->assertSame($issue->assigned_to['type'], $attributeProperty->getValue($issueObject->assignedTo())['type']);
        $this->assertSame($issue->assigned_to['name'], $attributeProperty->getValue($issueObject->assignedTo())['name']);
        $this->assertSame($issue->assigned_to['email'], $attributeProperty->getValue($issueObject->assignedTo())['email']);
    }
}
