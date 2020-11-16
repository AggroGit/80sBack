<?php

namespace App\Http\Middleware;


use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use App\Http\Controllers\Controller;
use Laravel\Passport\TokenRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use DateTime;
use Closure;
use Hash;
use Auth;





/*
|--------------------------------------------------------------------------
| checkToken
|--------------------------------------------------------------------------
|
*/

class checkToken
{
  /**
   * depending if is a web route or an api we are going to return an error code
   * or a redirect
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */

   private $server;

   public function __construct(ResourceServer $server)
    {
        $this->server = $server;
    }


  public function handle($request, Closure $next)
  {

      // $psr = (new DiactorosFactory)->createRequest($request);
      //
      //  try{
      //      $psr = $this->server->validateAuthenticatedRequest($psr);
      //  } catch (OAuthServerException $e) {
      //      throw new AuthenticationException;
      //  }
      //
      //  foreach ($scopes as $scope) {
      //     if (!in_array($scope,$psr->getAttribute('oauth_scopes'))) {
      //       throw new AuthenticationException;
      //     }
      //   }
      //
      //  return $nextç($request);



   }
 }
