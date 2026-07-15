<?php

/**
 * This file is auto-generated. DO NOT EDIT.
 */

declare(strict_types=1);

namespace MaxAntipin\YandexCloud\SDK\Service;

use MaxAntipin\YandexCloud\SDK\Service\Resource\ResourceManager\CloudResource;
use MaxAntipin\YandexCloud\SDK\Service\Resource\ResourceManager\FolderResource;
use Thesis\Grpc\Client as GRPCClient;
use Yandex\Cloud\Resourcemanager\V1\CloudServiceClient;
use Yandex\Cloud\Resourcemanager\V1\FolderServiceClient;

final class ResourceManagerService implements APIServiceInterface
{
    public private(set) CloudResource $cloud {
        get {
            return $this->cloud ??= new CloudResource(new CloudServiceClient($this->client));
        }
    }

    public private(set) FolderResource $folder {
        get {
            return $this->folder ??= new FolderResource(new FolderServiceClient($this->client));
        }
    }

    public function __construct(
        private readonly GRPCClient $client,
    ) {
    }

    public function __destruct()
    {
    }
}
