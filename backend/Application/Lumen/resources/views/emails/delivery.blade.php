@extends('layouts.cms')

@section('content')
    <div class="card border-primary mb-3">
        <div class="card-header">
            <h4>Новая поставка по заявке № {{$invoice->getRequisition()->getNumber()}} от {{ Carbon\Carbon::parse($invoice->getRequisition()->getCreatedAt())->format('d-m-Y') }}</h4>
            @if( Auth::user()->getRoles()->getService() == 'snabzenie' )
                <p>
                    <a href="{{env('FRONT_URL')}}/crm/supply/requisition/free/info/{{$invoice->getRequisition()->getId()->serialize()}}/material">
                        Заявка {{ $invoice->getRequisition()->getNumber() }} от {{ Carbon\Carbon::parse($invoice->getRequisition()->getCreatedAt())->format('d-m-Y') }}
                    </a>
                </p>

            @elseif( Auth::user()->getRoles()->getService() == 'master' )
                <p>
                    <a href="{{env('FRONT_URL')}}/crm/master/requisition/info/{{$invoice->getRequisition()->getId()->serialize()}}/material">
                        Заявка {{ $invoice->getRequisition()->getNumber() }} от {{ Carbon\Carbon::parse($invoice->getRequisition()->getCreatedAt())->format('d-m-Y') }}
                    </a>
                </p>

            @elseif( Auth::user()->getRoles()->getService() == 'upravlenie' )
                <p>
                    <a href="{{env('FRONT_URL')}}/crm/upravlenie/requisition/info/{{$invoice->getRequisition()->getId()->serialize()}}/material">
                        Заявка {{ $invoice->getRequisition()->getNumber() }} от {{ Carbon\Carbon::parse($invoice->getRequisition()->getCreatedAt())->format('d-m-Y') }}
                    </a>
                </p>
            @endif
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Дата поставки: <b>{{ Carbon\Carbon::parse($invoice->getDeliveryAt())->format('d-m-Y') }}</b></li>
            @if ($invoice->getStock())
            <li class="list-group-item">Склад:  <b>{{ $invoice->getStock()->getName() }}</b></li>
            @endif
            @if ($invoice->getDescription())
                <li class="list-group-item">Комментарий:  <b>{{ $invoice->getDescription() }}</b></li>
            @endif
            <li class="list-group-item">
                @if ($invoice->getRequisition()->getAutor()->getWorkpeople())
                    Автор заявки:  <b>
                        {{ $invoice->getRequisition()->getAutor()->getWorkpeople()->getSurname() }}
                        {{ $invoice->getRequisition()->getAutor()->getWorkpeople()->getName() }}
                        {{ $invoice->getRequisition()->getAutor()->getWorkpeople()->getPatronymic() }} </b>{{ $invoice->getRequisition()->getAutor()->getUsername() }}<b> ({{$invoice->getRequisition()->getAutor()->getRoles()->getName()}})
                    </b>
                @else
                    Автор заявки:  <b>{{ $invoice->getRequisition()->getAutor()->getUsername() }}</b>
                @endif
            </li>
            <li class="list-group-item">
                @if ($invoice->getRequisition()->getSpecification())
                    <span>Спецификация: <b>{{$invoice->getRequisition()->getSpecification()->getName()}}</b></span>
                    <span>Объект: <b>{{$invoice->getRequisition()->getSpecification()->getObject()->getName()}}</b></span>
                @else
                    <span>Спецификация: <b>нет</b></span>
                @endif

            </li>
        </ul>

        <div class="card-body">
            <table class="table table-sm table-striped table-bordered" width="80%">
                <thead>
                <tr>
                    <td colspan="5" style="align-content: center; font-weight: bold; border: 0px ">Материалы</td>
                </tr>
                <tr>
                    <th cope="col">П/П</th>
                    <th cope="col">Материал</th>
                    <th cope="col">Кол-во</th>
                    <th cope="col">Ед.изм</th>
                    <th cope="col">Комментарий</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($invoice->getMaterials() as $idx=>$material)
                    <tr>
                        <th scope="row">
                            {{$idx + 1}}
                        </th>
                        <td>
                            @if ($material->getSpecificationMaterial())
                                {{$material->getSpecificationMaterial()->getFullname()}}
                            @elseif ($material->getDirectoryMaterial())
                                {{$material->getDirectoryMaterial()->getName()}}
                            @else
                                {{$material->getName()}}
                            @endif

                        </td>
                        <td>
                            {{ $material->getQuantity() }}
                        </td>
                        <td>
                            @if ($material->getSpecificationMaterial())
                                {{ $material->getSpecificationMaterial()->getUnit() ? $material->getSpecificationMaterial()->getUnit()->getTitle() : '' }}
                            @elseif ($material->getDirectoryMaterial())
                                {{ $material->getDirectoryMaterial()->getUnit() ? $material->getDirectoryMaterial()->getUnit()->getTitle() : '' }}
                            @else

                                {{ $material->getUnit() ? $material->getUnit()->getTitle() : '' }}
                            @endif
                        </td>
                        <td>
                            {{$material->getDescription()}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    @if ($invoice->getRequisition()->getManager()->getWorkpeople())
                        Менеджер:  <b>
                            {{ $invoice->getRequisition()->getManager()->getWorkpeople()->getSurname() }}
                            {{ $invoice->getRequisition()->getManager()->getWorkpeople()->getName() }}
                            {{ $invoice->getRequisition()->getManager()->getWorkpeople()->getPatronymic() }} </b>{{ $invoice->getRequisition()->getManager()->getUsername() }}<b> ({{$invoice->getRequisition()->getManager()->getRoles()->getName()}})
                        </b>
                    @else
                        Менеджер:  <b>{{ $invoice->getRequisition()->getManager()->getUsername() }}</b>
                    @endif
                </li>
            </ul>
        </div>
    </div>
@endsection

