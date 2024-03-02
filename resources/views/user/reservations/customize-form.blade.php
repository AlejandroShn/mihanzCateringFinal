@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Services.css') }}">
<div class="filter">
    <div class="customization">
        <div class="display-2 m-5" style="font-family: 'Playfair Display', serif;">Reservation Form</div>

        <form action="{{ route('user.reservations.store') }}" method="post">
            @csrf
            <div class="input-container">
                <table class="table">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <tr>
                        <th scope="row">No. of Guest</th>
                        <td>
                            <input type="number" class="form-control" name="pax" placeholder="Minimum of 50" aria-label="Guest" aria-describedby="basic-addon1" min="50"
                                value="{{ old('pax', session('pax')) }}">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Budget</th>
                        <td>
                            <input type="number" class="form-control" name="budget" placeholder="Minimum of 18,000" aria-label="Budget" aria-describedby="basic-addon1"
                                min="18000" value="{{ old('budget', session('budget')) }}">
                        </td>
                    </tr>

                </tbody>
            </table>
            <div id="guestMessageContainer">
                <p id="guestMessage" class="fs-4 mb-5 text-center"></p>
            </div>
            <div style="display: flex; justify-content: flex-end;">
                <button type="button" class="btn btn-primary m-5" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Proceed</button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="display-6 fs-3 fw-semibold"> Do you want to proceed?</div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Yes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const budgetInput = document.querySelector('input[name="budget"]');
                const guestMessageContainer = document.getElementById('guestMessageContainer');
                const guestMessage = document.getElementById('guestMessage');

                budgetInput.addEventListener('input', function () {
                    const budget = parseFloat(budgetInput.value) || 0;

                    if (budget >= 18000) {
                        const additionalGuests = Math.floor((budget - 18000) / 350);
                        const totalGuests = 50 + additionalGuests;
                        guestMessage.innerHTML =  `Additional guests will be charged 350 pesos per head, <br>Prices will be subject to change.`;
                        guestMessageContainer.style.display = 'block';
                    } else {
                        guestMessage.innerHTML = `Additional guests will be charged 350 pesos per head, <br>Prices will be subject to change.`;
                        guestMessageContainer.style.display = 'block';
                    }
                });
            });

        </script>

    </div>
</div>
</div>
@endsection
