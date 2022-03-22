@if (session()->has('message'))
<div class="alert alert-success">
{{ session('message') }}
</div>
@endif

@if (session()->has('error'))
<div class="alert alert-danger">
{{ session('error') }}
</div>
@endif
<form>
                            <div class="form-group">
                                <label for="emailaddress">Email address</label>
                              
                                <input id="email" type="email" wire:model="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your Email address">

                                @error('email') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-muted float-right"><small>Forgot your password?</small></a>
                                @endif
                                <label for="password">Password</label>
                                <div class="input-group input-group-merge">
                                <input id="password" wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your Password">

                                    <div class="input-group-append @error('password') is-invalid @enderror" data-password="false">
                                        <div class="input-group-text @error('password') is-invalid @enderror">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>

                                    @error('password') <span class="invalid-feedback text-danger error">{{ $message }}</span>@enderror



                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"  name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">Remember me</label>
                                </div>
                            </div>
                            <div class="form-group mb-0 text-center">
                                <button class="btn  btn-warning btn-block" wire:click.prevent="login">LOGIN </button>
                            </div>
                            <!-- social-->
                           
                        </form>
                        