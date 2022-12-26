// console.log(window.location)
let carpeta = 'SIE2023'
let protocol = window.location.protocol

const Lugar = {
    backend: window.location.protocol+'//'+window.location.hostname+'/'+carpeta+'/backend/',
    banners: window.location.protocol+'//'+window.location.hostname+'/'+carpeta+'/banners/'
}

export default Lugar