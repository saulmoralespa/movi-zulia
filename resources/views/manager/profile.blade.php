@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 4em">
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s4"><a href="{{ route('manager.settings') }}#perfil" class="active active-settings">Perfil</a></li>
                    <li class="tab col s4"><a href="{{ route('manager.settings.password') }}#password">Contraseña</a></li>
                </ul>
            </div>
            {{-- Perfil --}}
            <div id="profile" class="col s12">
                <form action="{{ route('manager.settings') }}" enctype="multipart/form-data"  method="POST" accept-charset="UTF-8">
                    @csrf
                    <div class="row center-align">
                        <div class="col s12">
                            <h4>Avatar</h4>
                            <p>Puedes subir la imagen de tu perfil</p>
                        </div>
                        <div class="col s12">
                            <div>
                                <img alt="" width="200px" src="{{ Storage::url( Auth::user()->avatar) }}">
                            </div>
                            <div>
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>Subir Imagen</span>
                                        <input type="file" name="avatar_user" id="avatar_user">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col s12">
                            <h4>Ajustes Principales</h4>
                        </div>
                        <div class="col s12">
                            <div class="row">
                                <div class="input-field col s9">
                                    <label for="name_user">Name</label>
                                    <input required type="text" value="{{ Auth::user()->name }}" name="name_user" id="name_user">
                                </div>
                                <div class="input-field col s3">
                                    <label for="id_user">User ID</label>
                                    <input readonly="readonly" type="text" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                                </div>
                            </div>
                            <div class="input-field s12">
                                <label for="email_user">Email</label>
                                <input required value="{{ Auth::user()->email }}" type="email" name="email_user" id="email_user">
                            </div>
                            @role('manager')
                            <div class="row">
                                <div class="input-field col s3">
                                    <label for="name_cooperative">Nombre Cooperativa</label>
                                    <input required value="{{ Auth::user()->name_cooperative }}" type="text" name="name_cooperative" id="name_cooperative">
                                </div>
                                <div class="input-field col s9">
                                    <textarea name="description_cooperative" maxlength="100" data-length="100" id="description_cooperative" class="materialize-textarea">{{ Auth::user()->description_cooperative }}</textarea>
                                    <label for="description_cooperative">Descripción Cooperativa</label>
                                </div>
                            </div>
                            @endrole
                            <div class="input-field s12">
                                <input type="submit" name="commit" value="Actualizar la configuración del perfil" class="waves-effect waves-light btn white-text">
                                <a class="waves-effect waves-light btn" href="{{ route('home') }}">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
