<?php


use App\Token;

class AuthenticationTest extends FeatureTestCase
{
    // un usuario puede iniciar sesion con una url con token
    function test_a_user_can_login_with_a_token_url()
    {
        // Having (teniendo)
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        // When (cuando)
        $this->visitRoute("login", ['token' => $token->token]);

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
}
