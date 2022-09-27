<?php

declare(strict_types=1);

namespace Sfp\Deptrac\Builtin\Dependency\Emitter;

use Qossmic\Deptrac\Core\Ast\AstMap\AstMap;
use Qossmic\Deptrac\Core\Ast\AstMap\ClassLike\ClassLikeReference;
use Qossmic\Deptrac\Core\Ast\AstMap\DependencyToken;
use Qossmic\Deptrac\Core\Ast\AstMap\File\FileReference;
use Qossmic\Deptrac\Core\Ast\AstMap\FunctionLike\FunctionLikeReference;
use Qossmic\Deptrac\Core\Ast\AstMap\FunctionLike\FunctionLikeToken;
use Qossmic\Deptrac\Core\Dependency\Dependency;
use Qossmic\Deptrac\Core\Dependency\DependencyList;
use Qossmic\Deptrac\Core\Dependency\Emitter\DependencyEmitterInterface;

use function assert;

final class OptInBuiltinFunctionCallDependencyEmitter implements DependencyEmitterInterface
{
    private array $supports;
    private bool $fallbackGlobal;

    public function __construct(array $supports = ['header'], bool $fallbackGlobal = true)
    {
        $this->supports = $supports;
        $this->fallbackGlobal = $fallbackGlobal;
    }

    public function getName(): string
    {
        return 'OptInBuiltinFunctionCallDependencyEmitter';
    }

    public function applyDependencies(AstMap $astMap, DependencyList $dependencyList): void
    {
        $this->createDependenciesForReferences($astMap->getClassLikeReferences(), $astMap, $dependencyList);
        $this->createDependenciesForReferences($astMap->getFunctionLikeReferences(), $astMap, $dependencyList);
        $this->createDependenciesForReferences($astMap->getFileReferences(), $astMap, $dependencyList);
    }

    /**
     * @param array<FunctionLikeReference|ClassLikeReference|FileReference> $references
     */
    private function createDependenciesForReferences(array $references, AstMap $astMap, DependencyList $dependencyList): void
    {
        foreach ($references as $reference) {
            foreach ($reference->getDependencies() as $dependency) {
                if (DependencyToken::UNRESOLVED_FUNCTION_CALL !== $dependency->getType()) {
                    continue;
                }

                $token = $dependency->getToken();
                assert($token instanceof FunctionLikeToken);

                if (null === $astMap->getFunctionReferenceForToken($token) &&
                    ! in_array($token->toString(), $this->supports, true)) {
                    
                    if (! $this->fallbackGlobal) {
                        continue;
                    }
                    
                    // fallback check. e.g. `Foo\Action\setcookie()`
                    [$function] = array_reverse(explode('\\', $token->toString()));
                    if (! in_array($function, $this->supports, true)) {
                        continue;
                    }
                }

                $dependencyList->addDependency(
                    new Dependency(
                        $reference->getToken(),
                        $dependency->getToken(),
                        $dependency->getFileOccurrence()
                    )
                );
            }
        }
    }
}
