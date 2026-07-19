<?php

/**
 * This file is auto-generated. DO NOT EDIT.
 */

declare(strict_types=1);

namespace MaxAntipin\YandexCloud\SDK\Service;

use MaxAntipin\YandexCloud\SDK\Service\Resource\DNS\DnsFirewallResource;
use MaxAntipin\YandexCloud\SDK\Service\Resource\DNS\DnsInboundEndpointResource;
use MaxAntipin\YandexCloud\SDK\Service\Resource\DNS\DnsZoneResource;
use Thesis\Grpc\Client as GRPCClient;
use Yandex\Cloud\Dns\V1\DnsFirewallServiceClient;
use Yandex\Cloud\Dns\V1\DnsInboundEndpointServiceClient;
use Yandex\Cloud\Dns\V1\DnsZoneServiceClient;

final class DNSService implements APIServiceInterface
{
    public private(set) DnsFirewallResource $dnsFirewall {
        get {
            return $this->dnsFirewall ??= new DnsFirewallResource(new DnsFirewallServiceClient($this->client));
        }
    }

    public private(set) DnsInboundEndpointResource $dnsInboundEndpoint {
        get {
            $a = 5;
            return $this->dnsInboundEndpoint ??= new DnsInboundEndpointResource(
                new DnsInboundEndpointServiceClient($this->client)
            );
        }
    }

    public private(set) DnsZoneResource $dnsZone {
        get => $this->dnsZone ??= new DnsZoneResource(new DnsZoneServiceClient($this->client));
    }

    public function __construct(
        private readonly GRPCClient $client,
    ) {
    }
}
