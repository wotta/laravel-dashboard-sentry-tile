<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Factories;

use Wotta\SentryTile\Models\Issue;
use Wotta\SentryTile\Objects\Issue as IssueObject;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Issue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'project_id' => '1',
            'external_id' => $this->faker->numerify(str_repeat('#', 10)),
            'title' => $this->faker->title,
            'status' => $this->faker->randomElement(IssueObject::STATUSES),
            'type' => $this->faker->randomElement(['default', 'error']),
            'level' => $this->faker->randomElement(['info', 'warning', 'error', 'fatal']),
            'logger' => $this->faker->randomElement(['', 'local', 'staging', 'production']),
            'first_seen' => $this->faker->dateTimeBetween('-28 days', '-5 days'),
            'last_seen' => $this->faker->dateTimeBetween('-20 days', '-2 days'),
            'permalink' => $this->faker->url,
            'meta' => $this->generateMeta(),
            'assigned_to' => $this->generateAssignedTo(),
        ];
    }

    /**
     * @return mixed
     */
    protected function generateMeta()
    {
        return $this->faker->randomElement([
            ['title' => $this->faker->title],
            [
                'type' => sprintf('Wotta\\Exceptions\\%sException', $this->faker->word),
                'value' => $this->faker->sentence(3),
                'filename' => sprintf(
                    '/%s/%s/%s.php',
                    $this->faker->word,
                    $this->faker->word,
                    $this->faker->word,
                ),
                'function' => sprintf(
                    '%s\\%s\\%s::%s%s',
                    ucfirst($this->faker->word),
                    ucfirst($this->faker->word),
                    ucfirst($this->faker->word),
                    $this->faker->word,
                    ucfirst($this->faker->word)
                ),
            ],
        ]);
    }

    /**
     * @return mixed
     */
    protected function generateAssignedTo()
    {
        return $this->faker->randomElement([
            null,
            [
                'id' => $this->faker->numerify(str_repeat('#', 6)),
                'name' => $this->faker->name,
                'type' => 'user',
                'email' => $this->faker->email,
            ],
        ]);
    }
}
