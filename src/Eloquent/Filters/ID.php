<?php
/**
 * Copyright 2020 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace LaravelJsonApi\Eloquent\Filters;

use InvalidArgumentException;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use function explode;
use function is_string;

class ID implements Filter
{

    /**
     * @var string
     */
    private string $column;

    /**
     * Create an ID filter.
     *
     * @param string $column
     * @return static
     */
    public static function make(string $column): self
    {
        return new self($column);
    }

    /**
     * ID constructor.
     *
     * @param string $column
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }

    /**
     * @inheritDoc
     */
    public function key(): string
    {
        return 'id';
    }

    /**
     * @inheritDoc
     */
    public function isSingular(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function apply($query, $value)
    {
        return $query->whereIn(
            $query->qualifyColumn($this->column),
            $this->deserialize($value)
        );
    }

    /**
     * Deserialize resource ids.
     *
     * The id filter can either be a comma separated string of resource ids, or an
     * array of resource ids.
     *
     * @param array|string|null $resourceIds
     * @return array
     */
    protected function deserialize($resourceIds): array
    {
        if (is_string($resourceIds)) {
            return explode(',', $resourceIds);
        }

        if (!is_array($resourceIds)) {
            throw new InvalidArgumentException('Expecting a string or array.');
        }

        return $resourceIds;
    }

}