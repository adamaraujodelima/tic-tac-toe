<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;

use App\Match;

class MatchController extends Controller {

    public function index() {
        return view('index');
    }

    /**
     * Returns a list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function matches() {
        $matches = $this->getMatches();
        return response()->json($matches);
    }

    /**
     * Returns the state of a single match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function match($id) {
        
        $response = [];

        $match = Match::find($id);

        if($match){
            $response = array(
                'id' => $match->id,
                'name' => $match->name . ' ' . $match->id,
                'next' => $match->next,
                'winner' => $match->winner,
                'board' => json_decode($match->board, true),
            );
        }

        return response()->json($response);

    }

    /**
     * Makes a move in a match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($id) {
        $match = Match::find($id);

        $board = json_decode($match->board, true);
        $player = $next = $match->next;

        switch ($player) {
            case '1':
                $next = '2';
                break;
            case '2':
                $next = '1';
                break;
        }

        $position = Input::get('position');
        $checkPosition = $board[$position] == 0 && $board[$position] != $player;

        if($checkPosition){
            $board[$position] = $player;
            if(array_sum($board) > 0){
                $winner = $this->checkWinner($board);
                $response = Match::where('id', $id)->update([
                    'next' => $next,
                    'winner' => $winner,
                    'board' => json_encode($board)
                ]);
            }
        }
        
        return $this->match($id);

    }

    private function checkWinner($board){
        
        $winner = 0;

        $options = [
            'option1' => $board[0] * $board[1] * $board[2],
            'option2' => $board[0] * $board[3] * $board[6],
            'option3' => $board[0] * $board[4] * $board[8],
            'option4' => $board[1] * $board[4] * $board[7],
            'option5' => $board[2] * $board[5] * $board[8],
            'option6' => $board[2] * $board[4] * $board[6],
            'option7' => $board[3] * $board[4] * $board[5],
            'option8' => $board[6] * $board[7] * $board[8],
        ];        

        foreach($options as $option){
            switch ($option) {
                case 1:
                    $winner = 1;
                    break;
                case 8:
                    $winner = 2;
                    break;
            }
        }

        return $winner;
    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create() {
        
        $Match = new Match;
        $Match->name = 'Match';
        $Match->next = 1;
        $Match->board = json_encode([0, 0, 0, 0, 0, 0, 0, 0, 0]);
        $Match->save();

        $matches = $this->getMatches();

        return response()->json($matches);
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id) {       
        
        try {            
            $match = Match::find($id);    
            if ($match) {
                $match->delete();
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $matches = $this->getMatches();

        return response()->json($matches);
    }

    /**
     * Get all available matches
     *
     * @return \Illuminate\Support\Collection
     */
    private function getMatches() {        
        
        $matches = [];

        $rows = Match::where('winner','=',0)->get();

        foreach ($rows as $key => $match) {
            $matches[] = array(
                'id' => $match->id,
                'name' => $match->name . ' ' . $match->id,
                'winner' => $match->winner                
            );
        }
        
        return collect($matches);
    }

}