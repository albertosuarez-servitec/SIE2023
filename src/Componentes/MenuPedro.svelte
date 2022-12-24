<script>
  import {link, push, location} from 'svelte-spa-router' 
  import axios from 'axios';
  import Swal from 'sweetalert2'

  let usuario_correo = ''
  let usuario_clave= ''
  let correoValido = false
  let claveValida = false
  let usuarioActual = ''
  let idUsuario = 0

  const login = async () => {
    const res = await axios.post('http://localhost/sie2023/backend/login.php',{
      correo_existente: usuario_correo,
      password_correcto: usuario_clave
    })
  }
// para el correo
  export const validarCorreo = (string) => {
        let out = '';
        let filtro = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_@.-';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out += string.charAt(i);
        return out
    }

    export function contarEspeciales(string){
        let out = 0;
        let filtro = '@.';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++
        return out
    }
// para la contraseña
  

  export const letrasNumerosSignos = (string) => {
        let out = '';
        let filtro = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890#$%&.-_{}[]*@';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out += string.charAt(i);
        return out
    }

    export const contarAltas = (string) =>{
        let out = 0;
        let filtro = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++
        return out
    }

    export const contarBajas = (string) => {
        let out = 0;
        let filtro = 'abcdefghijklmnopqrstuvwxyz'
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++;
        return out
    }

    export const contarNumeros = (string) => {
        let out = 0;
        let filtro = '1234567890';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++;
        return out
    }

    export const contarSignos = (string) => {
        let out = 0;
        let filtro = '#$%&.-_{}[]*@.';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++;
        return out
    }

// correo
  const revisarCorreo = () =>{
    usuario_correo=usuario_correo.trim()
        usuario_correo=usuario_correo.toLowerCase()
        usuario_correo=validarCorreo(usuario_correo)
        if (contarEspeciales(usuario_correo)>1) {
            correoValido = true
        } else {
            correoValido = false
        }
  }

  const alertaCorreo = () => {
        if ( !correoValido && usuario_correo.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El correo no es válido',
                footer: 'Revísalo para poder ingresar.'
            })
        }
    }
// clave
    const revisarClave = () =>{
        usuario_clave = usuario_clave.trim()
          usuario_clave = letrasNumerosSignos(usuario_clave)
          if (usuario_clave.length > 0 && usuario_clave.length < 8) {
              claveValida = false
          } else if (usuario_clave.length > 7) {
              if (contarAltas(usuario_clave)>0 && contarBajas(usuario_clave)>0 && contarNumeros(usuario_clave)>0 && contarSignos(usuario_clave)>0) {
                  claveValida = true
              }
          }
    }

    const alertaClave = () => {
        if ( !claveValida && usuario_clave.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La contraseña no es válida, al menos debe tener 8 caracteres usando una mayúscula, una minúscula, un número y un símbolo de estos: #$%&.-_{}[]*@.',
                footer: 'Revísala para poder ingresar.'
            })
        }
    }



  

</script>

<main>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="#!">ENCRyM</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#!navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <button type="button" class="btn btn-light { $location == '/' ? 'active' : ''}" on:click={()=>push('/')}>Inicio</button>
              </li>
              <li class="nav-item">
                <!-- <button type="button" class="btn btn-light" on:click={()=>push('/')}>Acerca de...</button> -->
               <!-- Boton de acerca de-->
                  <button type="button" class="btn btn-light acerca" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Acerca de...
                  </button>

                  <!-- Modal acerca de-->
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Acerca del sistema</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Este sistema es administrado por la Escuela Nacional de Conservación, Restauración y Museografía “Manuel del Castillo Negrete” (ENCRyM). Algunos derechos reservados 2022.
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">cerrar</button>
                        </div>
                      </div>
                    </div>
                  </div>
              </li>
            </ul>
            <form class="d-flex">
              <!-- Boton modal de entrar -->
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
               Entrar
              </button>

              <!-- Modal entrar-->
              <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Inicio de sesion</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Correo ENCRyM</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" bind:value={usuario_correo} on:input={revisarCorreo} on:change={alertaCorreo}>
                        <div id="emailHelp" class="form-text">Recuerda Ingrear con el correo institucional.</div>
                      </div>
                      <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" bind:value={usuario_clave} on:input={revisarClave} on:change={alertaClave}>
                      </div>
                      <a href="#!">¿Has olvidado tu contraseña?</a>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      <button type="button" class="btn btn-primary " on:click={login} >Entrar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </nav>
</main>

<style>
.acerca{
  margin-left: 4px;
}
</style>