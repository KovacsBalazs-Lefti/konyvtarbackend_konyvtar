<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Models\Rental;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $books = Book::all();
        return response()->json(["data" => $books]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        //új könyv létrehozása az össszes beírt adattal
        $book = new Book($request->all());
        $book->save();
        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    //rent függvény megvalósítása
    public function rent(Request $request, string $id)
    {
        //megkeressük a könyvet id alapján
        //utána ha nem létezik a könyv 404-el térünk vissza
        $book = Book::find($id);
        if (is_null($book)) {
            return response()->json(["message" => "A könyv nem található a $id -val "], 404);
        }
        $rentals = Rental::where([
            //id a megadott book_id = id
            ["book_id", $id],
            //kezdő dátum kisebb vagy egyenlő mint az aktuális dátum
            ["start_date", "<=", date("Y-m-d")],
            //a vége dátum a mai dátummal azonos vagy nagyobb
            ["end_date", ">=", date("Y-m-d")],
        ])->get();

        //ha rentals
        if (!$rentals->isEmpty()) {
            return response()->json(["message"=>"Jelenleg a könyv ki van kölcsönözve"], 409);
        }
        //ha nincs kiadva létrehozzuk a foglalást
        $rental = new Rental();
        $rental->book_id = $id;
        $rental->start_date = date("Y-m-d");
        $rental->end_date = date("Y-m-d", strtotime("+1 week"));
        $rental->save();

        return response()->json($rental, 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
