<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Swagger\Swagger;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

/**
 * Class SwaggerController
 *
 * @author Augusto Gerardo Sotelo Labarca <agsotelo@gmail.com>
 *
 */
class SwaggerController extends Controller {
	
	/**
	 * GET config('swagger-lume.routes.api')
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function interface() {
		if (config ( 'swagger-lume.generate_always' )) {
			\SwaggerLume\Generator::generateDocs ();
		}
		
		if (config ( 'swagger-lume.proxy' )) {
			$proxy = (new Request ())->server ( 'REMOTE_ADDR' );
			(new Request ())->setTrustedProxies ( [
					$proxy
			] );
		}
		
		$extras = [ ];
		$conf = config ( 'swagger-lume' );
		if (array_key_exists ( 'validatorUrl', $conf )) {
			// This allows for a null value, since this has potentially
			// desirable side effects for swagger. See the view for more
			// details.
			$extras ['validatorUrl'] = $conf ['validatorUrl'];
		}
		
		// need the / at the end to avoid CORS errors on Homestead systems.
		$response = new Response ( view ( 'swagger-lume::index', [
				'apiKeyPrefix' => config ( 'swagger-lume.api.auth_token_prefix' ),
				'apiKey' => config ( 'swagger-lume.api.auth_token' ),
				'apiKeyVar' => config ( 'swagger-lume.api.key_var' ),
				'apiKeyInject' => config ( 'swagger-lume.api.key_inject' ),
				'secure' => (new Request ())->secure (),
				'urlToDocs' => url ( config ( 'swagger-lume.routes.docs' ) ),
				'requestHeaders' => config ( 'swagger-lume.headers.request' )
		], $extras ), 200 );
		
		if (is_array ( config ( 'swagger-lume.headers.view' ) ) && ! empty ( config ( 'swagger-lume.headers.view' ) )) {
			foreach ( config ( 'swagger-lume.headers.view' ) as $key => $value ) {
				$response->header ( $key, $value );
			}
		}
		
		return $response;
	}
	
	/**
	 * GET config('swagger-lume.routes.docs')
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function doc() {
		$page = 'api-docs.json';
		$filePath = config ( 'swagger-lume.paths.docs' ) . "/{$page}";
		
		if (File::extension ( $filePath ) === '') {
			$filePath .= '.json';
		}
		
		if (! File::exists ( $filePath )) {
			App::abort ( 404, "Cannot find {$filePath}" );
		}
		
		$content = File::get ( $filePath );
		
		return new Response ( $content, Response::HTTP_OK, [
				'Content-Type' => 'application/json'
		] );
	}
}
