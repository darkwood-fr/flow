# Changelog

## v1.2.3

- Add event Event::POOL occurs when Flow needs to count IPs to process.
- Add `Flow\IpPool` for managing pools of Ips.
- Update `Flow\Event\PullEvent` to pull multiple Ips instead one.
- Move `Flow::do` to `FlowFactory::create`
- Add `Flow\Driver\ParallelDriver`

## v1.2.2

- Flow can now use `Flow\JobInterface` as job input
- Add Symfony Bridge
    - new `Flow\Attribute\AsJob` attribute allows cast job on function or class and embed it's name and description

## v1.2.1

- Add new Interface Flow\AsyncHandlerInterface to control the Event::SYNC step when processing an IP
    - Add Flow\AsyncHandler\AsyncHandler the default handler when Event::SYNC is dispatched
    - Add Batch IP with Flow\AsyncHandler\BatchAsyncHandler from
        - https://speakerdeck.com/alli83/symfony-messenger-et-ses-messages-a-la-queleuleu-dot-dot-dot-et-sil-etait-temps-de-grouper
        - https://wolfgang-klinger.medium.com/how-to-handle-messages-in-batches-with-symfony-messenger-c91b5aa1c8b1
        - https://github.com/wazum/symfony-messenger-batch
    - Add Flow\AsyncHandler\DeferAsyncHandler to gain granular control on the async Event::SYNC step event
- Flow\Flow\YFlow rework
- Add more exemples in `examples/yflow.php` to play with Y-Combinators
- Update DX for Flow\DriverInterface : add `defer` to gain much granular control on asynchronous callbacks

## v1.2.0

- Add event system for processing IpStrategy
- Remove `start` and `stop` in favor of `await` for Flow\DriverInterface

## v1.1.5

- Add Flow\FlowInterface::do notation from https://github.com/fp4php/functional
- Update Flow\FlowInterface::fn to accept as first argument
    - Closure : it's the job itself
    - array : constructor arguments for Flow instanciation
    - array (view as shape) : configuration for Flow instanciation
    - FlowInterface : the FlowInterface instance itself
    - array : map of all possible above choices
- Update to Symfony 7.0

## v1.1.4

- Add generic templating
- Add Flow\Driver\SpatieDriver
- Add more quality tools from https://github.com/IngeniozIT/php-skeleton

## v1.1.3

- Update DX for Flow\DriverInterface
    - Update `async` that now $onResolve get called with async $callback result or Flow\Exception as first argument
    - Update `tick` that now return a Closure to cleanup tick interval
- Add Flow\Driver\FiberDriver from https://github.com/jolicode/castor/blob/main/src/functions.php
- Upgrade to Symfony 6.3 and PHPUnit 10.3
- Refactor docs structure
- Refactor tooling from https://github.com/jolicode/castor

## v1.1.2

- Update to PHP 8.2
- Upgrade from amphp/amp v2 to amphp/amp v3 that use PHP Fibers
- Upgrade from react/event-loop v1 to reactphp/async v4 that use PHP Fibers
- Upgrade from Swoole v5 to Openswoole v22
- Rename function `corouting` to `async` in Flow\DriverInterface
- Add function `sleep` in Flow\DriverInterface

## v1.1.1

- Rename entire project from `Railway FBP` to `Flow`
- Bundle `Flow` to PHP monorepository
- Merge from `packages/symfony` to `packages/php` and make Flow [Symfony](https://symfony.com) friendly
- New DX interface `Flow\FlowInterface`
- Error managment is now integrated to Flow
- Remove context associated with processing IP
- Deprecate `Flow\Flow\SequenceFlow` in favor for `Flow\Flow\Flow`
- Deprecate `Flow\Flow\ErrorFlow` in favor for `Flow\Flow\Flow`
- Update `Flow\Flow\YFlow` and make it plain native
- Update `Flow\IP` that use readonly object
- New Flow logo

## v1.1.0

- Release MIT License
- Update dependencies to PHP 8.1

## v1.0.9

- Add `Flow\Flow\YFlow` that allows introduce recursivity into Flow language approach

## v1.0.8

- Add code of conduct

## v1.0.7

- Define Monads

## v1.0.6

- Add `Flow\TransportFlow`
- Flow can process multiple jobs in parallel

## v1.0.5

- Add Symfony integration
- Define monads

## v1.0.4

- Refactor structure
- Decouple integration

## v1.0.3

- Add `Flow\IpStrategy` add several Ip strategy for data processing

## v1.0.2

- Add `Flow\Driver\DriverInterface`
- Add `Flow\Driver\AmpDriver`
- Add `Flow\Driver\ReactDriver`
- Add `Flow\Driver\SwooleDriver`

## v1.0.1

- Add `Flow\Producer\CollectionConsumer`
- Add `Flow\Producer\CollectionProducer`
- Add `Flow\Transport\CollectionTransport`

## v1.0.0

- Initial release
