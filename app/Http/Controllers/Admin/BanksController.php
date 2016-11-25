<?php

namespace codeFin\Http\Controllers\Admin;

use codeFin\Http\Controllers\Controller;
use codeFin\Http\Controllers\Response;
use Illuminate\Http\Request;

use codeFin\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use codeFin\Http\Requests\BankCreateRequest;
use codeFin\Http\Requests\BankUpdateRequest;
use codeFin\Repositories\BankRepository;



class BanksController extends Controller
{

    /**
     * @var BankRepository
     */
    protected $repository;   

    public function __construct(BankRepository $repository) // remoer sempre , BankValidator $validator
    {
        $this->repository = $repository;       
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = $this->repository->paginate(5); // paginação parecido com o all()
        //dd($banks);

        /*if (request()->wantsJson()) {

            return response()->json([
                'data' => $banks,
            ]);
        }*/

        return view('admin.banks.index', compact('banks'));
    }

    // metodo apenas para mostrar o formulario
    public function create(){
        return view('admin.banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BankCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BankCreateRequest $request)
    {       
            $data = $request->all();
            $data['logo'] = md5(time()).'jpg'; 
            $this->repository->create($data);           

            /*if ($request->wantsJson()) {

                $response = [
                'message' => 'Bank created.',
                'data'    => $bank->toArray(),
                ];

                return response()->json($response);
            }*/

            //return redirect()->back()->with('message', $response['message']);
            return redirect()->route('admin.banks.index');
        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $bank = $this->repository->find($id);

        return view('banks.edit', compact('bank'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  BankUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(BankUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $bank = $this->repository->update($id, $request->all());

            $response = [
                'message' => 'Bank updated.',
                'data'    => $bank->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Bank deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Bank deleted.');
    }
}