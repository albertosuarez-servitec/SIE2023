// console.log(window.location)
let carpeta = 'SIE2023'
let protocol = window.location.protocol

const Lugar = {
    backend: window.location.protocol+'//'+window.location.hostname+'/'+carpeta+'/backend/',
    banners: window.location.protocol+'//'+window.location.hostname+'/'+carpeta+'/banners/',
    fotos: window.location.protocol+'//'+window.location.hostname+'/'+carpeta+'/fotos/'
}

export default Lugar