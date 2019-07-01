@extends('layouts.main')

@section('content')
    <br>
    <div class="row">
        <div class="col-sm-12">
            @if(count($errors))
                <div class="alert alert-danger">
                    @foreach($errors->all() as $key => $error)
                        <li>{{$error}}</li> 
                    @endforeach
                </div>
            @endif


            @if(session()->has('success'))
                <div class="alert alert-success">
                    <li>{{session()->get('success')}}</li> 
                </div>
            @endif

            <form method="post" class="row" action="{{ !empty($transaction) ? route('transaction.update', [$transaction->id]) : route('transaction.store') }}">
                <label class="col-sm-12">Transaction Form</label>
                @csrf
                @if(!empty($transaction))
                    @method('PUT')
                @endif

                @php   
                    if(!empty($transaction)){
                        $date = $transaction->date->format('Y-m-d');
                        $name = $transaction->name;
                        $description = $transaction->description;
                        $amount = $transaction->amount;
                        $type = $transaction->type;
                    }else{
                        $date = NULL;
                        $name = NULL;
                        $description = NULL;
                        $description = NULL;
                        $amount = NULL;
                        $type = NULL;
                    }


                    $date = old('date') ?: $date;
                    $name = old('name') ?: $name;
                    $description = old('description') ?: $description;
                    $amount = old('amount') ?: $amount;
                    $type = old('type') ?: $type;
                    
                @endphp
                <div class="form-group col-md-2">                    
                    <input type="date" class="form-control" name="date" id="date" value="{{$date }}">
                </div>
                <div class="form-group col-md-2">                    
                    <input type="text" class="form-control" name="name" id="name" value="{{$name}}" placeholder="Account Name">
                </div>
                <div class="form-group col-md-2">                    
                    <input type="text" class="form-control" name="description" id="description" value="{{$description}}" placeholder="Description">
                </div>
                <div class="form-group col-md-2">                    
                    <input type="number" step="any" class="form-control" name="amount" id="amount" value="{{$amount}}" placeholder="Amount">
                </div>
                <div class="form-group col-md-2">                     
                    <select class="form-control" name="type" id="type">
                        <option value="" {{ $type == null ? 'slected' : '' }}>Income/Expense</option>
                        <option value="{{ INCOME }}" {{ $type == INCOME ? 'slected' : '' }}>Income</option>
                        <option value="{{ EXPENSE }}" {{ $type == EXPENSE ? 'slected' : '' }}>Expense</option>
                    </select>
                </div>
                <div class="form-group col-md-2">                    
                    <button class="btn btn-primary">Post</button>
                </div>
            </form>
        </div> 
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12">
            <form method="" class="row" action="{{route('transaction.index')}}">                
                <div class="form-group col-md-1">                    
                    <label class="col-sm-12">Filter Results</label>
                </div>
                <div class="form-group col-md-2">                    
                    <input value="{{$request->get('from')}}" type="date" class="form-control" name="from" id="from" placeholder="From Date">
                </div>
                <div class="form-group col-md-2">                    
                    <input value="{{$request->get('to')}}" type="date" class="form-control" name="to" id="to" placeholder="To Date">
                </div>
                <div class="form-group col-md-2">                    
                    <input value="{{$request->get('name')}}" type="text" class="form-control" name="name" id="name" placeholder="Account Name">
                </div>
                <div class="form-group col-md-2">
                    <select class="form-control" name="type" id="type">
                        <option value="" {{!$request->get('type') ? 'selected' : ''}}>Income/Expense</option>
                        <option value="{{ INCOME }}" {{$request->get('type') == INCOME ? 'selected' : ''}}>Income</option>
                        <option value="{{ EXPENSE }}" {{$request->get('type') == EXPENSE ? 'selected' : ''}}>Expense</option>
                    </select>
                </div>
                <div class="form-group col-md-3">                    
                    <button class="btn btn-success">Apply</button>
                    <a href="{{route('transaction.index')}}" class="btn btn-danger">Clear</a>
                </div>
            </form>
        </div> 

        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $balance = 0;
                            $total_amount = 0;
                            $total_balance = 0;
                        @endphp
                        @foreach ($transactions as $transaction)
                            @php
                                $multiple = $transaction->type == INCOME ? 1 : -1;
                                $amount = $multiple * $transaction->amount;
                                $balance += $amount;

                                $total_amount += $transaction->amount;
                                $total_balance += $balance;
                            @endphp

                            <tr>
                                <td>{{ $transaction->date->format('M d, Y') }}</td>
                                <td>{{ $transaction->name }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ $transaction->amount }}</td>
                                <td>{{ $transaction->type == INCOME ? 'Income' : 'Expense' }}</td>
                                <td>{{ $balance }}</td>
                                <td>
                                    <form action="{{ route('transaction.destroy', [$transaction->id]) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <a class="btn btn-primary" href="{{ route('transaction.edit', [$transaction->id]) }}">Edit</a>
                                        <button class="btn btn-danger" href="" >Del</button>
                                    </form>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>                        
                        <tr>                            
                            <th colspan="3" class="text-right">Total</th>
                            <th>{{ $total_amount }}</th>
                            <th></th>
                            <th>{{ $total_balance }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection