<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Yj\Factory;

use Hyperf\Filesystem\Contract\AdapterFactoryInterface;
use Hyperf\Filesystem\Version;
use League\Flysystem\Adapter\Local;

class LocalAdapterFactory implements AdapterFactoryInterface
{
    public function make(array $options)
    {

        return new Local($options['root'], LOCK_EX, self::DISALLOW_LINKS, $this->permissions);
    }

    /**
     * @var int
     */
    const DISALLOW_LINKS = 0002;

    /**
     * @var array
     */
    protected $permissions = [
        'file' => [
            'public' => 0644,
            'private' => 0600,
        ],
        'dir' => [
            'public' => 0755,
            'private' => 0700,
        ],
    ];
}
