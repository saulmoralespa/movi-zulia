@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 4em">
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s4"><a href="{{ route('manager.settings') }}#perfil" class="active active-settings">Perfil</a></li>
                    <li class="tab col s4"><a href="{{ route('manager.settings.password') }}#password" class="active active-settings">Contraseña</a></li>
                </ul>
            </div>
            {{-- Contraseña --}}
            <div id="password" class="col s12">
                <div class="col s12 center-align">
                    <h4>Contraseña</h4>
                    <p>Después de una actualización exitosa de la contraseña, se le redirigirá a la página de inicio de sesión donde puede iniciar sesión con su nueva contraseña.</p>
                    @if (session('error'))
                        <div class="card-panel red darken-1 white-text">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="col s12">
                    <h5>Cambiar contraseña</h5>
                    <form action="{{ route('manager.settings.changePassword') }}" method="POST">
                        @csrf
                        <div class="input-field">
                            <label for="new_password">Nueva Contraseña</label>
                            <input required type="password" name="new_password" id="new_password" class="validate {{ $errors->has('password') ? ' is-invalid' : '' }}">
                        </div>
                        <div class="input-field">
                            <label for="new_password_confirm">Confirmar Contraseña</label>
                            <input required type="password" name="new_password_confirm" id="new_password_confirm" class="validate {{ $errors->has('password') ? ' is-invalid' : '' }}">
                        </div>
                        <div class="input-field">
                            <input type="submit" name="commit" value="Guardar Contraseña" class="waves-effect waves-light btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
