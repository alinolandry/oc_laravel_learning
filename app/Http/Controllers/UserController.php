<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

use App\Repositories\UserRepository;

use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userRepository;

    protected $nbrePerPage = 4;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->getPaginate($this->nbrePerPage);
        $links = $users->render();

        return view('index', compact('users', 'links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $this->setAdmin($request);

        $user = $this->userRepository->store($request->all());

        return redirect('user')->withOk("L'utilisateur ". $user->name ." a été crée.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepository->getById($id);

        return view('show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->getById($id);
         return view('edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $this->setAdmin($request);

        $this->userRepository->update($id, $request->all());

        return redirect('user')->withOk("L'utilisateur ".$request->input('name'). " a été modifié");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->userRepository->destroy($id);

        return back();
    }

    private function setAdmin($request)
    {
        if(!$request->has('admin'))
		{
			$request->merge(['admin' => 0]);
		}
    }
}
