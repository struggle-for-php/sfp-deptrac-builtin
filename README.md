# Deptrac extension to enforce application on framework rule.

PHP Web application on framework should not use network function like `header()`. 
But this rule is **Implicit** !.
This extension is attempted to find violate such rule.

## Example

 - examples/src/Action/UserShowAction.php includes
```
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        \header('Location: http://www.example.com/');
    }
```

```
$ cd examples/
$ ./vendor/bin/deptrac analyse
 107/107 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

 ----------- ----------------------------------------------------------------------------------
  Reason      Action
 ----------- ----------------------------------------------------------------------------------
  Violation   Foo\Action\UserShowAction must not depend on header() (Sending Raw HTTP)
              /home/sasezaki/dev/sfp-deptrac-builtin/examples/src/Action/UserShowAction.php:20
 ----------- ----------------------------------------------------------------------------------


 -------------------- -----
  Report
 -------------------- -----
  Violations           1
  Skipped violations   0
  Uncovered            24
  Allowed              48
  Warnings             0
  Errors               0
 -------------------- -----
 ```

 ## Q&A

Q. Why not use PHPCS `forbiddenFunctionNames` ?
A. IMO, `forbiddenFunctionNames` is only should be used for dangerous function (its own) or alias function.
Reason of forbidden `header()` usage in application is layer problem.

Q. When called `header()`, then should be called `exit()` on same scope, is it not ?
A. Maybe..


## Inspired 
This extension is developed inspired from this talk.
https://speakerdeck.com/asumikam/phpcon-2022