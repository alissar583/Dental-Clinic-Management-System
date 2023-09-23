<!DOCTYPE html>
<html>

<head>
    <title>DCM Medical Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        .row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* تقسيم العناصر إلى 3 أعمدة متساوية العرض */
            grid-gap: 10px;
            /* الفراغ بين العناصر */
        }

        .column {
            /* أي تنسيقات أخرى ترغب في تطبيقها على العناصر */
        }
    </style>
</head>

<body>
    <div class="container-md">
        <p style=" margin-left: auto;
        margin-right: auto;
        width: 150px">
            {{ __('Medical Report') }}</p>
        <p>{{ __('OID') . ': ' . $oid }}</p>

        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('phone') }}</th>
                        <th scope="col">{{ __('Birth Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $user['full_name'] }}</td>
                        <td>{{ $user['phone'] }}</td>
                        <td>{{ $user['birth_date'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            <span
                style="  display: flex;
            justify-content: center; margin-left:auto; margin-right: auto; width: 15em"
                class="badge badge-secondary">{{ __('Medical History') }}</span>
            <hr style="margin-top: 5px">
            <div>
                @foreach ($all_illnesses as $illness)
                    <div class="form-check col-3 form-check-inline">
                        <input class="form-check-input" type="checkbox"
                            @foreach ($illnesses as $patientIllness)
                        @if ($illness['id'] == $patientIllness['id'])
                            checked
                        @endif @endforeach
                            id="inlineCheckbox1" value="option1">
                        <label class="form-check-label"
                            for="inlineCheckbox1">{{ $illness['name_' . app()->getLocale()] }}</label>
                    </div>
                @endforeach

            </div>
            <div>
                {{-- @if ($else_illnesses) --}}
                <p>{{ '- ' . __('Else Illnesses') . ': ' . $else_illnesses }}</p>
                {{-- @endif --}}
                {{-- @if ($medicine) --}}
                <p>{{ '- ' . __('Medicine') . ': ' . $medicine }}</p>
                {{-- @endif --}}
            </div>

        </div>
        <div style="margin-top: 10px;">
            @foreach ($treatements['data'] as $index => $treatement)
                <div class="card  border">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="d-inline-block col-5">
                                <span class="d-inline-block">
                                    {{ __('Doctor Name') . ': ' . $treatement['doctor_first_name'] . ' ' . $treatement['doctor_last_name'] }}
                                </span>
                                <span class="d-inline-block">
                                    {{ __('Doctor Phone') . ': ' . $treatement['doctor_phone'] }}
                                </span>
                            </div>
                            <div class="d-inline-block col-5">
                                <span
                                    class="d-inline-block">{{ __('Preview Name') . ': ' . $treatement['preview_name'] }}
                                </span>

                                <span
                                    class="d-inline-block">{{ __('Preview Cost') . ': ' . $treatement['preview_cost'] }}
                                </span>
                            </div>
                        </div>
                        @if (count($treatement['reservations']) > 0)
                            <div class="ml-4">
                                <div>
                                    @foreach ($treatement['reservations'] as $index => $reservation)
                                        <div>
                                            <div class="card">
                                                <div class="card-header">
                                                    {{ $reservation['date'] }}
                                                </div>
                                                <div class="card-body">
                                                    {{-- <p>{{ __('Diagnostics') . ': ' . $reservation['diagnostics'] }}
                                                </p>
                                                <p>{{ __('Medicines') . ': ' . $reservation['medicines'] }}</p> --}}

                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center active"
                                                            style="background-color: #146C94;border: none;  height: 25px">
                                                            <p>{{ __('Diagnostics') }}</p>
                                                        </li>
                                                        <li class="list-group-item d-flex">
                                                            <small
                                                                class="rounded-pill p-2 align-items-left">{{ $reservation['diagnostics'] }}</small>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center active"
                                                            style="background-color: #146C94;border: none;  height: 25px">
                                                            <p>{{ __('Medicines') }}</p>
                                                        </li>
                                                        <li class="list-group-item d-flex">
                                                            <small
                                                                class="rounded-pill p-2 align-items-left">{{ $reservation['medicines'] }}</small>
                                                        </li>

                                                        <li class="list-group-item d-flex justify-content-between align-items-center active"
                                                            style="background-color: #146C94;border: none;  height: 25px">
                                                            <p>{{ __('Files From Doctor') }}</p>
                                                        </li>
                                                        @foreach ($reservation['media'] as $index => $media)
                                                            @if ($media['collection_name'] == 'doctorReservationMedia')
                                                                <li class="list-group-item d-flex">
                                                                    <a style="color:#146C94" class="mr-auto p-2"
                                                                        href="{{ $media->getFullUrl() }}">
                                                                        {{ $media['name'] }}
                                                                    </a>
                                                                    <small
                                                                        class="rounded-pill p-2 align-items-left">{{ $media['created_at'] }}</small>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                        <li class="list-group-item d-flex justify-content-between align-items-center active"
                                                            style="background-color: #146C94;border: none; height: 25px">
                                                            <p>{{ __('Files From Patient') }}</p>
                                                        </li>
                                                        @foreach ($reservation['media'] as $index => $media)
                                                            @if ($media['collection_name'] == 'patientReservationMedia')
                                                                <li class="list-group-item d-flex">
                                                                    <a class="mr-auto p-2"
                                                                        href="{{ $media->getFullUrl() }}">
                                                                        {{ $media['name'] }}
                                                                    </a>
                                                                    <small
                                                                        class="rounded-pill p-2 align-items-left">{{ $media['created_at'] }}</small>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
