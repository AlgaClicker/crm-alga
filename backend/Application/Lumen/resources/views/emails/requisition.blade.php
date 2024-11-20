@extends('layouts.cms')

@section('content')
        <div class="card border-primary mb-1">
            <div class="card-header">
                <h3>
                    {{$title}}
                </h3>
                @if( $role== 'snabzenie' )
                    <p>
                        <a href="{{env('FRONT_URL')}}/crm/supply/requisition/free/info/{{$requisition->getId()->serialize()}}/material">
                            Заявка {{ $requisition->getNumber() }} от {{ Carbon\Carbon::parse($requisition->getCreatedAt())->format('d-m-Y') }}
                        </a>
                    </p>

                @elseif( $role == 'master' )
                    <p>
                        <a href="{{env('FRONT_URL')}}/crm/master/requisition/info/{{$requisition->getId()->serialize()}}/material">
                            Заявка {{ $requisition->getNumber() }} от {{ Carbon\Carbon::parse($requisition->getCreatedAt())->format('d-m-Y') }}
                        </a>
                    </p>

                @elseif( $role == 'upravlenie' )
                    <p>
                        <a href="{{env('FRONT_URL')}}/crm/upravlenie/requisition/info/{{$requisition->getId()->serialize()}}/material">
                            Заявка {{ $requisition->getNumber() }} от {{ Carbon\Carbon::parse($requisition->getCreatedAt())->format('d-m-Y') }}
                        </a>
                    </p>

                @endif

                @if ($requisition->getAutor()->getWorkpeople())
                    Автор:  <b>
                        {{ $requisition->getAutor()->getWorkpeople()->getSurname() }}
                        {{ $requisition->getAutor()->getWorkpeople()->getName() }}
                        {{ $requisition->getAutor()->getWorkpeople()->getPatronymic() }} </b> {{ $requisition->getAutor()->getUsername() }}<b> ({{$requisition->getAutor()->getRoles()->getName()}})
                    </b>
                @else
                    Автор:  <b>{{ $requisition->getAutor()->getUsername() }}</b>
                @endif
                доставить к <b> {{ Carbon\Carbon::parse($requisition->getEndAt())->format('d.m.Y') }}</b>
                <br>
                Статус: {{$requisition->getStatus()}}<br>
                @if ($requisition->getManager())
                    Менеджер: <b>
                        @if ($requisition->getManager()->getWorkpeople())
                            {{ $requisition->getManager()->getWorkpeople()->getSurname() }}
                            {{ $requisition->getManager()->getWorkpeople()->getName() }}
                            {{ $requisition->getManager()->getWorkpeople()->getPatronymic() }} ({{$requisition->getManager()->getRoles()->getName()}})
                        @else
                            {{ $requisition->getManager()->getUsername() }}
                        @endif
                    </b>
                @endif
                @if ($requisition->getSpecification())
                    <hr>
                    <span>Спецификация: <b>{{$requisition->getSpecification()->getName()}}</b></span>
                    <span>Объект: <b>{{$requisition->getSpecification()->getObject()->getName()}}</b></span>
                @endif
                @if ($requisition->getDescription())
                    <hr>
                    <p class="card-text"><i>Комментарий:</i> {{$requisition->getDescription()}}</p>
                @endif

            </div>

            <div class="card-body">
                <div class="card" style="width: 100%;">
                    <div class="card-header  text-center">
                        Материалы
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped table-bordered" width="80%">
                            <thead>
                            <tr class=" text-center">
                                <th cope="col" class="text-center align-middle" style="width: 3rem;" >П/П</th>
                                <th cope="col" >Наименование</th>
                                <th cope="col">Кол-во</th>
                                <th cope="col">Ед.изм</th>
                                <th cope="col">Комментарий</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($requisition->getMaterials()->toArray() as $idx=>$material)
                                <tr>
                                    <th scope="row" class="text-center align-middle" >
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
                                    <td class="text-center align-middle col-1">
                                        {{ $material->getQuantity() }}
                                    </td>
                                    <td class="text-center align-middle">
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
                </div>

            </div>

            @if ($requisition->getSpecification())
            <div class="card-footer text-muted">
                <ul  class="list-group list-group-flush">Ответственные по спецификации:
                @foreach ($requisition->getSpecification()->getResponsibles() as $responsible)
                    <li class="list-group-item">
                        @if ($responsible->getWorkpeople())
                                {{ $responsible->getWorkpeople()->getSurname() }}
                                {{ $responsible->getWorkpeople()->getName() }}
                            {{ $responsible->getWorkpeople()->getPatronymic() }} {{ $responsible->getUsername() }}<b> ({{$responsible->getRoles()->getName()}}) </b>
                            <br>
                        @else
                            {{ $responsible->getUsername() }} <b> ({{$responsible->getRoles()->getName()}}) </b>
                        @endif
                    </li>
                @endforeach
                </ul>
            </div>
            @endif
        </div>
@endsection
