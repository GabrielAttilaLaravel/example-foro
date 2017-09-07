<?php

use App\Repositories\voteRepository;
use App\Vote;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class APostCanBeVotedTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $post;

    function setUp()
    {
        parent::setUp();
        // generamos un usuario y simulamos la coneccion
        $this->actingAs($this->user = $this->defaultUser());

        $this->post = $this->createPost();
    }

    // un post puede tener un punto positivo
    function test_a_post_can_be_upvoted(){
        // llamamos a la funcion upvote para poder votar por le post
        // Vote::upvote($this->post);
        // cargamos el repositorio para poder votar por le post
        app(voteRepository::class)->upvote($this->post);

        // verificamos si hay cambio en una tabla espesifica en la DB
        $this->assertDatabaseHas('votes', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'vote'    => 1
        ]);

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(1, $this->post->score);
    }

    // un usuario no puede votar por el mismo post 2 veces de forma positiva
    function test_a_post_cannot_be_upvoted_twice_by_the_same_user(){
        // llamamos a la funcion upvote para poder votar por le post
        // Vote::upvote($this->post);
        // cargamos el repositorio para poder votar por le post
        app(voteRepository::class)->upvote($this->post);

        // Vote::upvote($this->post);
        // cargamos el repositorio para poder votar por le post
        app(voteRepository::class)->upvote($this->post);
        // verificamos si hay cambio en una tabla espesifica en la DB
        /**$this->assertDatabaseHas('votes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'vote'    => 1
        ]);**/
        $this->assertSame(1, Vote::count());

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(1, $this->post->score);
    }

    // un post puede tener un punto negativo
    function test_a_post_can_be_downvoted(){
        // llamamos a la funcion downVote para darle un punto negativo al post
        // Vote::downVote($this->post);
        // cargamos el repositorio para darle un punto negativo al post
        app(voteRepository::class)->downVote($this->post);

        // verificamos si hay cambio en una tabla espesifica en la DB
        $this->assertDatabaseHas('votes', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'vote'    => -1
        ]);

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(-1, $this->post->score);
    }

    // un usuario no puede votar por el mismo post 2 veces de forma negativa
    function test_a_post_cannot_be_downvoted_twice_by_the_same_user(){
        // llamamos a la funcion upvote para poder votar por le post
        // Vote::downVote($this->post);
        // cargamos el repositorio para darle un punto negativo al post
        app(voteRepository::class)->downVote($this->post);

        //Vote::downVote($this->post);
        // cargamos el repositorio para darle un punto negativo al post
        app(voteRepository::class)->downVote($this->post);

        $this->assertSame(1, Vote::count());

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(-1, $this->post->score);
    }

    // un usuario quiere cambiar su voto de positivo a negativo
    function test_a_user_ca_switch_from_upvote_to_downvote(){
        // llamamos a la funcion upvote para poder votar por le post
        // Vote::upvote($this->post);
        // cargamos el repositorio para poder votar por le post
        app(voteRepository::class)->upvote($this->post);

        // Vote::downVote($this->post);
        // cargamos el repositorio para darle un punto negativo al post
        app(voteRepository::class)->downVote($this->post);

        $this->assertSame(1, Vote::count());

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(-1, $this->post->score);
    }

    // un usuario quiere cambiar su voto de negativo a positivo
    function test_a_user_ca_switch_from_downvote_to_upvote(){
        // llamamos a la funcion downVote para darle un punto negativo al post
        // Vote::downVote($this->post);
        // cargamos el repositorio para darle un punto negativo al post
        app(voteRepository::class)->downVote($this->post);

        // Vote::upvote($this->post);
        // cargamos el repositorio para crear un voto
        app(voteRepository::class)->upvote($this->post);

        $this->assertSame(1, Vote::count());

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(1, $this->post->score);
    }

    // calculamos los votos del post de forma correcta
    function test_the_post_score_is_calculated_correctly(){
        // creamos un post de forma directa de cualquier usuario
        Vote::create([
            'post_id' => $this->post->id,
            'user_id' => $this->anyone()->id,
            'vote' => 1
        ]);

        // registramos un voto con le usuario conectado
        // Vote::upvote($this->post);
        // cargamos el repositorio para crear un voto
        app(voteRepository::class)->upvote($this->post);

        $this->assertSame(2, Vote::count());

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(2, $this->post->score);
    }

    // eliminar un voto
    function test_a_post_can_be_unvoted(){
        // creamos un post de forma directa
        // Vote::upvote($this->post);
        // cargamos el repositorio para crear el voto del post
        app(voteRepository::class)->upvote($this->post);


        // pasamos el post al cual vamos a eliminar el voto
        // Vote::undoVote($this->post);
        // cargamos el repositorio para eliminar el voto
        app(voteRepository::class)->undoVote($this->post);

        // verificamos que en la tabla votes no tenemos un voto con la siguiente cracteristica
        $this->assertDatabaseMissing('votes', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'vote' => 1
        ]);

        // verificamos el score del post para comprobar que tenemos 0
        $this->assertSame(0, $this->post->score);
    }
}