<?php


use App\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthenticationTest extends FeatureTestCase
{
    // un usuario puede iniciar sesion con una url con token
    function test_a_user_can_login_with_a_token_url()
    {
        // Having (teniendo)
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        // When (cuando)
        $this->visitRoute('login', ['token' => $token->token]);

        // Then (entonces)
        /** Afirmamos que el usuario es autenticado. */
        $this->seeIsAuthenticated()
            /** Afirmamos que el usuario es autenticado como el usuario dado. */
            ->seeIsAuthenticatedAs($user);

        // no deberiamos ver el token creado en la db despues del token
        $this->dontSeeInDatabase('tokens', [
            'id' => $token->id
        ]);

        $this->seePageIs('/');
    }

    function test_a_user_cannot_loggin_with_an_invalid_token()
    {
        // Having (teniendo)
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $invalidToken = str_random(60);

        // When (cuando)
        $this->visitRoute('login', ['token' => $invalidToken]);

        // Then (entonces)
        /** Afirmamos que el usuario no esta autenticado. */
        $this->dontSeeIsAuthenticated()
            /** redirigimos al usuario para que solicite otro token. */
            ->seeRouteIs('token')
            /** mostramos un error */
            ->see('Este enlace ya expiró, por favor solicita otro.');

        /**
         * Deberiamos ver en la DB el token todabia porque no a sido utilizado
         */
        $this->seeInDatabase('tokens', [
            'id' => $token->id
        ]);
    }

    function test_a_user_cannot_use_the_same_token_twice()
    {
        // Having (teniendo)
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        // autenticamos al usuario con el facade Auth y eliminamos el token despues de autenticar al usuario
        $token->login();

        /** cerramos sesion */
        Auth::logout();

        // When (cuando)
        $this->visitRoute('login', ['token' => $token->token]);

        // Then (entonces)
        /** Afirmamos que el usuario no esta autenticado. */
        $this->dontSeeIsAuthenticated()
            /** redirigimos al usuario para que solicite otro token. */
            ->seeRouteIs('token')
            /** mostramos un error */
            ->see('Este enlace ya expiró, por favor solicita otro.');

    }

    function test_the_token_expires_after_30_minutes()
    {
        // Having (teniendo)
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        /**
         * Simulamos que han pasado 30 min con Carbon::setTestNow
          */
        Carbon::setTestNow(Carbon::parse('+31 minutes'));

        // When (cuando)
        $this->visitRoute('login', ['token' => $token->token]);

        // Then (entonces)
        /** Afirmamos que el usuario no esta autenticado. */
        $this->dontSeeIsAuthenticated()
            /** redirigimos al usuario para que solicite otro token. */
            ->seeRouteIs('token')
            /** mostramos un error */
            ->see('Este enlace ya expiró, por favor solicita otro.');
    }

    function test_token_is_case_sensitive()
    {
        // Having (teniendo)
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        // When (cuando)
        $this->visitRoute('login', ['token' => strtolower($token->token)]);

        // Then (entonces)
        /** Afirmamos que el usuario no esta autenticado. */
        $this->dontSeeIsAuthenticated()
            /** redirigimos al usuario para que solicite otro token. */
            ->seeRouteIs('token')
            /** mostramos un error */
            ->see('Este enlace ya expiró, por favor solicita otro.');
    }
}
