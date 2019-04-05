<?php

namespace App\SecretsEncryption\DependencyInjection;

use App\SecretsEncryption\Encoding\EncoderFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('secrets_encryption');
        $rootNode
            ->validate()
                ->ifTrue(function ($v) { return !empty($v['encrypted_secrets']) && !isset($v['encryption_key']); })
                ->thenInvalid('To use "encrypted_secrets" you must set the "encryption_key".')
            ->end()
            ->children()
                ->enumNode('encoding')
                    ->values(EncoderFactory::getAvailableEncodings())
                    ->defaultValue(EncoderFactory::BASE64)
                    ->info('The binary encoding used for both the encryption key and the encrypted secrets.')
                ->end()
                ->scalarNode('encryption_key')
                    ->info('The encryption key used for encrypting and decrypting of secrets. It should NOT be committed in Git and only injected individually, e.g. via env variables.')
                ->end()
                ->arrayNode('encrypted_secrets')
                    ->validate()
                        ->ifTrue(function ($v) {
                            foreach ($v as $key => $value) {
                                if (!\is_string($key) || !\is_string($value)) {
                                    return true;
                                }
                            }

                            return false;
                        })
                        ->thenInvalid('The "encrypted_secrets" must be a map of name to encrypted secret using strings.')
                    ->end()
                    ->info('A map of name to encrypted secrets. All decrypted secrets will be available as DI parameters with their given names.')
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->scalarPrototype()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
