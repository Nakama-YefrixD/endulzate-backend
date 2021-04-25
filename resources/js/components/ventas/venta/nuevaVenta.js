import React from 'react'
import { Link } from 'react-router-dom'
import {Component} from 'react';
import Toast from 'react-bootstrap/Toast'
import Modal from 'react-bootstrap/Modal'

// IMPORT CONFRIM ALERTS
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css

// IMPORTAR SPINNER DE CARGA
import Loader from 'react-loader-spinner'

// IMPORTAR COMPONENTE DE PRODUCTOS AL DETALLE
import ProductoAddDetalleComponent from './productoAddDetalle'

// TOAST
import cogoToast from 'cogo-toast';

class NuevaVenta extends Component {
    
    constructor(){
        super();
        this.state ={
            getDni          : true,
            DNIRUCdatos     : [],
            DNIRUCdatos     : [],
            dniCliente      : '',
            
            // Toast
            estadoToast     : false,
            mensajeToast    : '',
            colorToast      : 'rgba(76,208,76,0.7)',

            // Cargando
            loading         : true,

            // Modales
            estadoModalEditarProducto   :false,

            // Array de productos agregados al detalle
            arrayProductosDetalle       : [],
            cantidadProductosDetalle    : 0,
            numeroProductoDetalle       : 0,
            tasks                       : '',
            productosDetalleList        : [],

            // DATOS TOTALES DE UNA VENTA
            tipoDocumentoCliente        : 1,
            numeroDocumentoCliente      : '',
            nombreCliente               : '',
            tipoComprobante             : '',
            numeroVentasRealizadas      : 0,
            totalVentasRealizadas       : 0,
            sucursalId                  : '',
            tipoMoneda                  : '1',
            numeroVenta                 : '',
            dateFactura                 : '',

            descuentoVentaRealizadoGuardado : 0,
            descuentoVentaRealizado     : 0,
            descuentoVenta              : 0,
            igvVenta                    : 0,
            subTotalVenta               : 0,
            totalVenta                  : 0,

            observacionVenta            : 'SN',
            
            // DATOS DE UN PRODUCTO TEMPORAL
            codigoProductoTemporal      : '',
            nombreProductoTemporal      : '',
            precioProductoTemporal      : '',

            estadoa:false,
            productos_tb                : [],
             //buscador y Paginate
             estadoBoton:[],
             estadoQuery:'',


        }
        this.getCambioNumeroDocumento        = this.getCambioNumeroDocumento.bind(this);
        this.getCambioNombreCliente          = this.getCambioNombreCliente.bind(this);
        this.cambiarTipoDocumento            = this.cambiarTipoDocumento.bind(this);
        this.cambiarTipoPago                 = this.cambiarTipoPago.bind(this);
        this.calcularSubTotalTotalDetalle    = this.calcularSubTotalTotalDetalle.bind(this);
        this.buscarCodigoProducto            = this.buscarCodigoProducto.bind(this);
        this.eliminarProductoDetalle         = this.eliminarProductoDetalle.bind(this);
        this.calcularDatosTotalesVenta       = this.calcularDatosTotalesVenta.bind(this);
        this.cambiarValorFormTemporales      = this.cambiarValorFormTemporales.bind(this);
        this.fetchAgregarProductoTemporal    = this.fetchAgregarProductoTemporal.bind(this);
        this.generarVentaInterna             = this.generarVentaInterna.bind(this);
        this.fetchAgregarVenta               = this.fetchAgregarVenta.bind(this);
        this.getCambioDescuentoVenta         = this.getCambioDescuentoVenta.bind(this);

        this.getProductos                    = this.getProductos.bind(this);
        this.handleChangeBuscador            = this.handleChangeBuscador.bind(this);
        this.seleccionarProductoTb           = this.seleccionarProductoTb.bind(this);
        this.cambiarCodigo                   = this.cambiarCodigo.bind(this);
        this.modalBuscarProducto             = this.modalBuscarProducto.bind(this);
        this.getCambioObservacion            = this.getCambioObservacion.bind(this);

    }

modalBuscarProducto() {
    this.setState({
        estadoa: !this.state.estadoa
    });
}

componentDidMount(){
    this.getProductos();
  }


getProductos(){
    fetch('/ventas/tb_buscarProducto')
        .then(res => res.json())
        .then(data => {
            this.setState({
                productos_tb: data
            })
            this.agregarBotones();
    })
}

agregarBotones(){
    let z = this.state.productos_tb.last_page;
    console.log(z);
    let x = [];
    if(z){
        for(let i = 1 ; i <= z ; i++){
            x.push(i);
        }
        this.setState({estadoBoton: x},()=>console.log(this.state.estadoBoton))
    }
}

cambiarPagina(number){
    fetch(`/ventas/tb_buscarProducto?page=${number}&buscar=${this.state.estadoQuery}`)
      .then(res => res.json())
      .then(data => {
          this.setState({productos_tb: data},()=>{
            console.log(this.state.productos_tb)
            this.agregarBotones();
          });

    })

  }

fetchQuery(query,name){
    fetch(`/ventas/tb_buscarProducto?${name}=${query}`)
      .then(res => res.json())
      .then(data => {
        this.setState({productos_tb: data,estadoQuery: query}, () => {
            this.agregarBotones();
            console.log("obtenido?");
        });
      })
}

handleChangeBuscador (e){

  const {name , value} = e.target;
  console.log(value);
  this.fetchQuery(value,name);
  console.log(name);
  console.log("ULTIMO ARRAY POSCION"+this.state.cantidadProductosDetalle)
}

getCambioObservacion (e){

    const {name , value} = e.target;
    this.setState({
        observacionVenta : value
    })
}

// SELECCIONAR UN PRODUCTO DE UNA TABLA
seleccionarProductoTb(codigo){

    this.buscarCodigoProducto(codigo, this.state.cantidadProductosDetalle-1)
    this.modalBuscarProducto()
}

cambiarCodigo(e, posicion){
    const {name , value} = e.target;

    this.state.productosDetalleList[posicion]['codigoProducto']    =    value;
    this.setState({
        productosDetalleList : this.state.productosDetalleList
    })
}

cambiarTipoDocumento(e){
    const {name , value} = e.target;

    this.setState({
        tipoDocumentoCliente : value
    })
    if(value == 0){
        this.setState({
            numeroDocumentoCliente  : 20000001,
            nombreCliente           : "NO IDENTIFICADO"
        })
    }
    console.log(this.state.tipoDocumentoCliente)
}

cambiarTipoPago(e){
    const {name , value} = e.target;

    this.setState({
        tipoMoneda : value
    })
    
}

getCambioNumeroDocumento(e){
    const {name , value} = e.target;
    
    this.setState({
        numeroDocumentoCliente  : value
    })

    if(this.state.tipoDocumentoCliente == 1 && value.length == 8){
        this.fetchDNIRUC("dni", value);
    }else if(this.state.tipoDocumentoCliente == 6 && value.length == 11){
        this.fetchDNIRUC("ruc", value);

    }else{
        this.setState({
            nombreCliente   : "NO IDENTIFICADO",
            numeroVentasRealizadas  : 0,
            totalVentasRealizadas   : 0,
            
        })
        
    }
}

fetchDNIRUC(tipoDocumento, value){
    let url = `/consult/`+tipoDocumento+`/${value}`;
    console.log(url)
    fetch(url,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token': csrf_token,
            }),
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',
            }
        }
    )
    .then(response => response.json())
    .then(data => {
        this.setState(
            {
                DNIRUCdatos: data['persona'],
            },
            () => {
                console.log(url);
                console.log(this.state.numeroDocumentoCliente);
                console.log("Datos:");
                console.log(data);
                if(data['response'] == true){
                    this.setState({
                        numeroVentasRealizadas  : data['numeroVentasRealizadas'],
                        totalVentasRealizadas   : data['totalVentasRealizadas'],
                    })
                    this.activarToast("DOCUMENTO CORRECTO", "rgba(76,208,76,0.7)");
                    console.log(this.state.DNIRUCdatos);
                    this.datosDNIRUC(tipoDocumento);

                }else{
                    this.activarToast("DOCUMENTO NO ENCONTRADO", "rgba(205,55,55,0.7)");
                }
                
            }
        );
        
    })
}

datosDNIRUC(tipoDocumento){
    if(tipoDocumento == "dni"){
        this.setState({
            nombreCliente   : this.state.DNIRUCdatos.nombres+" "+this.state.DNIRUCdatos.apellidoPaterno+" "+this.state.DNIRUCdatos.apellidoMaterno,
        },
        console.log(this.state.DNIRUCdatos))
    }else{
        this.setState({
            nombreCliente   : this.state.DNIRUCdatos.razonSocial,
        },
        console.log(this.state.DNIRUCdatos))
    }
}

getCambioNombreCliente(e){

    const {name , value} = e.target;
    this.setState({
        nombreCliente   : value
    })

}

activarToast(message, color) {
    this.setState({
        mensajeToast : message,
        colorToast   : color,
        estadoToast  : true
    });
}

activarConfirmacion(titulo, descripcion, btn_aceptar){
    confirmAlert({
        customUI: ({ onClose }) => {
            return (
                <div className='custom-ui'>
                    <h1>{titulo}</h1>
                    <p>{descripcion}</p>
                    <div className="form-group boton">
                        <div className="row">
                            <div className="col-6">
                            <button type="button" onClick={btn_aceptar}
                                className="btn waves-effect waves-light btn-rounded btn-primary btn-lg">
                                    Aceptar
                            </button>
                            </div>
                            <div className="col-6">
                                <button type="button" onClick={onClose}
                                    className="btn waves-effect waves-light btn-rounded btn-danger btn-lg">
                                        Cancelar
                                </button>
                                
                            </div>
                        </div>
                    </div>
                </div>
            );
        }
    });
}

cargando(titulo, descripcion, btn_aceptar){
    confirmAlert({
        customUI: ({ onClose }) => {
            return (
                <div className='custom-ui'>
                    <h1>{titulo}</h1>
                    <p>{descripcion}</p>
                    <div className="form-group boton">
                        <div className="row">
                            <div className="col-12">
                            <button type="button" onClick={onClose}
                                className="btn waves-effect waves-light btn-rounded btn-primary btn-lg">
                                    Aceptar
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            );
        }
    });
}

agregarProducto(posicion){
    this.state.cantidadProductosDetalle == 0
    ? this.agregarProductoDetalle()
    : confirmAlert({
        customUI: ({ onClose }) => {
            return (
                <div className='custom-ui'>
                    <h1>Agregar producto temporal</h1>
                    <p>Los productos temporales son agregados en el caso que no esten en el almacen</p>
                    <hr></hr>
                    <div className="row">
                        <div className="card-body">
                            <div className="row">
                                <div className="col-12">
                                    <label className="control-label col-form-label">Codigo del producto:</label>
                                    <input 
                                        type="text" 
                                        className="form-control is-valid" 
                                        autoFocus
                                        onChange={e => this.cambiarValorFormTemporales(e, 0)}
                                        // value={this.state.codigoProductoTemporal}

                                        />
                                </div>
                                <br/>
                                <div className="col-sm-12 col-md-6">
                                    <div className="form-group">
                                        <label className="control-label col-form-label">Producto:</label>
                                        <input 
                                            type="text" 
                                            className="form-control" 
                                            onChange={e => this.cambiarValorFormTemporales(e, 1)}
                                            // value={this.state.nombreProductoTemporal}
                                            />
                                    </div>
                                </div>
                                <div className="col-sm-12 col-md-6">
                                    <div className="form-group">
                                        <label className="control-label col-form-label">Precio: </label>
                                        <input 
                                            type="text" 
                                            className="form-control" 
                                            onChange={e => this.cambiarValorFormTemporales(e, 2)}
                                            // value={this.state.precioProductoTemporal}
                                            />
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div className="col-lg-12 col-md-12">
                            <button 
                                type="button" 
                                onClick={() => {
                                    this.fetchAgregarProductoTemporal(posicion);
                                    onClose();
                                }}
                                // onClick={()=>this.fetchAgregarProductoTemporal();  onClose();}
                                className="btn btn-block btn-lg btn-primary">Agregar</button>
                        </div>
                    </div>
                </div>
            );
        }
    });
}

cambiarValorFormTemporales(e, datoTemporal){
    const {name , value} = e.target;
    switch(datoTemporal){
        case 0:
            this.state.codigoProductoTemporal  = value;
            this.setState({
                codigoProductoTemporal : this.state.codigoProductoTemporal,
            })
            break;
        case 1:
            this.state.nombreProductoTemporal = value;
            this.setState({
                nombreProductoTemporal  : this.state.nombreProductoTemporal,
            })
            break;
        case 2:
            this.state.precioProductoTemporal = value;
            this.setState({
                precioProductoTemporal  : this.state.precioProductoTemporal
            })
            break;

    }
    console.log(value)
    console.log(this.state.codigoProductoTemporal)
    console.log(this.state.nombreProductoTemporal)
    console.log(this.state.precioProductoTemporal)


}

fetchAgregarProductoTemporal(posicion){
    // LA FUNCION AGREGA UN PRODUCTO TEMPORAL
    fetch(`/ventas/crear/productoTemporal`,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token'        : csrf_token,
                'codigoProductoNuevo'   : this.state.codigoProductoTemporal,
                'nombreProductoNuevo'   : this.state.nombreProductoTemporal,
                'precioVentaProducto'   : this.state.precioProductoTemporal,
            }),
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',
            }
        }
    )
    .then(response => response.json())
    .then(data => {
        console.log(data);
        console.log(data['response'])
        if(data['response'] == true){
            
            this.activarToast("Producto temporal agregado satisfactoriamente", "rgba(76,208,76,0.7)");
            this.state.precioProductoTemporal = '';
            this.state.nombreProductoTemporal = '';
            this.state.codigoProductoTemporal = '';
            this.setState({
                codigoProductoTemporal : this.state.codigoProductoTemporal,
                nombreProductoTemporal  : this.state.nombreProductoTemporal,
                precioProductoTemporal  : this.state.precioProductoTemporal,
            })

            var producto = data['producto'];

            this.state.productosDetalleList[posicion]['idProducto']        = producto['id'];
            this.state.productosDetalleList[posicion]['codigoProducto']    = producto['codigo'];
            this.state.productosDetalleList[posicion]['nombreProducto']    = producto['nombre'];
            this.state.productosDetalleList[posicion]['stockProducto']     = producto['cantidad'];
            this.state.productosDetalleList[posicion]['precioProducto']    = producto['precio'];
            this.state.productosDetalleList[posicion]['cantidadOferta']    = 0;
            this.state.productosDetalleList[posicion]['nuevoPrecioOferta'] = 0;
            this.state.productosDetalleList[posicion]['descuentoAplicado'] = 0;

            
            this.calcularSubTotalTotalDetalle(posicion, 1, producto['precio']);
            this.agregarProductoDetalle();
        }else{
            this.activarToast("Error al momento de agregar el producto temporal", "rgba(205,55,55,0.7)");
        }
        
    })
}

agregarProductoDetalle(){
    this.setState({
        cantidadProductosDetalle    : this.state.cantidadProductosDetalle+1,
        numeroProductoDetalle       : this.state.numeroProductoDetalle+1
    },()=>{ 
        // if(this.state.cantidadProductosDetalle > 1){ //CONDICION PARA QUE NO SE EJECUTE LA PRIMERA VEZ QUE SE AGREGA UN DETALLE
            this.setState({
                productosDetalleList    : [
                    ...this.state.productosDetalleList, 
                    {
                        idLista             : this.state.numeroProductoDetalle, 
                        idProducto          : 0,
                        codigoProducto      : '', 
                        nombreProducto      : '', 
                        cantidadProducto    : 1,
                        stockProducto       : 0,
                        precioProducto      : 0,
                        cantidadOferta      : 0,
                        nuevoPrecioOferta   : 0,
                        subTotalProducto    : 0,
                        totalProducto       : 0,
                        totalDescuento      : 0,
                        descuentoAplicado   : 0, //0 NO SE APLICA EL DESCUENTO, 1 SE APLICA EL DESCUENTO
                    } 
                ]
            })
        // }
        this.setState({
            arrayProductosDetalle   : [...this.state.arrayProductosDetalle, this.state.numeroProductoDetalle],
        })
    })
}

eliminarProductoDetalle(numero){
   console.log(numero);

   this.setState({
        cantidadProductosDetalle    : this.state.cantidadProductosDetalle-1
   })

   var array = [...this.state.arrayProductosDetalle];
   var index = array.indexOf(numero);

   if (index > -1) {
        array.splice(index,1);
        console.log(array);    
        this.setState({
            arrayProductosDetalle: array
        })
   }

   var arrayProductosDetalleList = [];

    this.state.productosDetalleList.map((data, posicion)=>{
        if(data.idLista != numero){
            arrayProductosDetalleList    = [
                ...arrayProductosDetalleList, 
                {
                    idLista             : data.idLista, 
                    idProducto          : data.idProducto,
                    codigoProducto      : data.codigoProducto,
                    nombreProducto      : data.nombreProducto,
                    cantidadProducto    : data.cantidadProducto,
                    stockProducto       : data.stockProducto,
                    precioProducto      : data.precioProducto,
                    cantidadOferta      : data.cantidadOferta,
                    nuevoPrecioOferta   : data.nuevoPrecioOferta,
                    subTotalProducto    : data.subTotalProducto,
                    totalProducto       : data.totalProducto,
                    totalDescuento      : data.totalDescuento,
                    descuentoAplicado   : data.descuentoAplicado
                } 
            ]
        }
    })

    this.setState({
        productosDetalleList: arrayProductosDetalleList
    })

    this.calcularDatosTotalesVenta();

}

buscarCodigoProducto(codigo, posicion){ 
    fetch(`/producto/buscar`,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token'        : csrf_token,
                codigoProducto  : codigo
            }),
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',
            }
        }
    )
    .then(response => response.json())
    .then(data => {
        console.log("datos:")
        console.log(data)
        if(data['response'] == true){
           
            this.activarToast("Producto agregado al detalle", "rgba(76,208,76,0.7)");
            
            var producto    = data['producto'];
            var descuento   = data['descuento'];

            this.state.productosDetalleList[posicion]['idProducto']        = producto['id'];
            this.state.productosDetalleList[posicion]['codigoProducto']    = producto['codigo'];
            this.state.productosDetalleList[posicion]['nombreProducto']    = producto['nombre'];
            this.state.productosDetalleList[posicion]['stockProducto']     = producto['cantidad'];
            this.state.productosDetalleList[posicion]['precioProducto']    = producto['precio'];

            if(descuento != 0){
                this.state.productosDetalleList[posicion]['cantidadOferta']    = descuento['cantidad'],
                this.state.productosDetalleList[posicion]['nuevoPrecioOferta'] = descuento['nuevoPrecio']
            }else{
                this.state.productosDetalleList[posicion]['cantidadOferta']    = 0,
                this.state.productosDetalleList[posicion]['nuevoPrecioOferta'] = 0
            }
            this.state.productosDetalleList[posicion]['totalDescuento']    = 0
            this.state.productosDetalleList[posicion]['descuentoAplicado']    = 0
            
            this.calcularSubTotalTotalDetalle(posicion, 1, producto['precio']);
            this.agregarProductoDetalle();
        }else{
            this.activarToast("Producto no encontrado", "rgba(205,55,55,0.7)");
            this.agregarProducto(posicion);
        }
        
    })
}

verArray(){
    // console.log('cambiar')
    // console.log(this.state.shop[1]['id'])
    // this.state.shop[1]['id'] = 11
    // this.setState({
    //     shop : this.state.shop
    // })
    // console.log('terminado')
    console.log(this.state.productosDetalleList)
}

getCambioDescuentoVenta(e){
    const {name , value} = e.target;
    this.setState({
        descuentoVentaRealizado  : value,
    })

    if (e.key === 'Enter') {
        this.setState({

            descuentoVenta : parseFloat(this.state.descuentoVenta) - parseFloat(this.state.descuentoVentaRealizadoGuardado),
            descuentoVentaRealizadoGuardado : value,
            descuentoVenta : parseFloat(this.state.descuentoVenta) + parseFloat(this.state.descuentoVentaRealizadoGuardado),
        })
        console.log(value);
    }
        
}

calcularSubTotalTotalDetalle(posicion, cantidad, precio){
    console.log('cambioCalcular');
    console.log(posicion)
    console.log(cantidad)
    console.log(precio)

    let precioCalcular;
    let descuento;
    if(cantidad >= this.state.productosDetalleList[posicion]['cantidadOferta'] && this.state.productosDetalleList[posicion]['cantidadOferta'] != 0  ){
        this.state.productosDetalleList[posicion]['descuentoAplicado'] = 1;
        precioCalcular  = this.state.productosDetalleList[posicion]['nuevoPrecioOferta'];
        descuento       = ((precio - precioCalcular)*cantidad).toFixed(2);

    }else{
        this.state.productosDetalleList[posicion]['descuentoAplicado'] = 0;
        precioCalcular  = precio;
        descuento       = 0
    }

    let total       = precioCalcular * cantidad;
    let subTotal    = total / 1.18;
    let impuestos   = total - subTotal;
    
    this.state.productosDetalleList[posicion]['cantidadProducto']  = cantidad;
    this.state.productosDetalleList[posicion]['subTotalProducto']  = subTotal.toFixed(2);
    this.state.productosDetalleList[posicion]['totalProducto']     = total.toFixed(2);
    this.state.productosDetalleList[posicion]['totalDescuento']    = descuento;
    
    
    

    var array = [...this.state.productosDetalleList];
    this.setState({
        productosDetalleList: array
    })
    this.calcularDatosTotalesVenta();
}

calcularDatosTotalesVenta(){
    
    this.state.descuentoVenta   = parseFloat(0.00)
    this.state.igvVenta         = parseFloat(0.00)
    this.state.subTotalVenta    = parseFloat(0.00)
    this.state.totalVenta       = parseFloat(0.00)
    
    this.setState({
        descuentoVenta  : this.state.descuentoVenta ,
        igvVenta        : this.state.igvVenta ,
        subTotalVenta   : this.state.subTotalVenta ,
        totalVenta      : this.state.totalVenta
    })

    this.state.productosDetalleList.map((data, posicion)=>{
        this.state.descuentoVenta   = parseFloat(this.state.descuentoVenta) + parseFloat(data.totalDescuento),
        this.state.igvVenta         = (parseFloat(data.totalProducto) - parseFloat(data.subTotalProducto)) + parseFloat(this.state.igvVenta),
        this.state.subTotalVenta    = parseFloat(this.state.subTotalVenta) + parseFloat(data.subTotalProducto),
        this.state.totalVenta       = parseFloat(this.state.totalVenta) + parseFloat(data.totalProducto);
    })

    this.setState({
        descuentoVenta  : this.state.descuentoVenta.toFixed(2),
        igvVenta        : this.state.igvVenta.toFixed(2) ,
        subTotalVenta   : this.state.subTotalVenta.toFixed(2) ,
        totalVenta      : this.state.totalVenta.toFixed(2)
    })

    console.log('TOTAL DE LA VENTA:'+this.state.totalVenta)
}

generarVentaInterna(){
    confirmAlert({
        customUI: ({ onClose }) => {
            return (
                <div className="page-content container-fluid">
                    <div className="row">
                        <div className="col-12">
                            <div className='card'>
                                <div className="card-body">
                                    <h1>¿Seguro que desea finalizar la venta?</h1>
                                    <p>Puedes imprimir un comprobante de pago, o solo guardarlo en el sistema.</p>
                                    <div className="form-group boton">
                                        <div className="row">
                                            <div className="col-lg-4 col-md-4">
                                                <button 
                                                    type="button" 
                                                    className="btn waves-effect waves-light btn-block btn-success"
                                                    onClick={() => {
                                                        this.fetchAgregarVenta(1);
                                                        onClose();
                                                    }}
                                                >
                                                        
                                                        Imprimir
                                                </button>
                                            </div>
                                            <div className="col-lg-4 col-md-4">
                                                <button 
                                                    type="button"
                                                    onClick={() => {
                                                        this.fetchAgregarVenta(0);
                                                        onClose();
                                                    }}
                                                    className="btn waves-effect waves-light btn-block btn-info">
                                                        Guardar
                                                </button>
                                            </div>
                                            <div className="col-lg-4 col-md-4">
                                                <button 
                                                    type="button" 
                                                    className="btn waves-effect waves-light btn-block btn-danger">
                                                        Cancelar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            );
        }
    });
}

fetchAgregarVenta(imprimir){ //SI IMPRIMIR ES 1 IMPRIMIR, SI NO, NO IMPRIMIR
    // console.log('DATOS ENVIADOS : --- ');
    // console.log(this.state.tipoDocumentoCliente);
    // console.log(this.state.numeroDocumentoCliente);
    // console.log(this.state.nombreCliente);
    // console.log(this.props.tipoComprobante);
    // console.log(this.props.sucursalId);
    // console.log(this.state.tipoMoneda);
    // console.log(this.props.numeroVenta);
    // console.log(this.state.descuentoVenta);
    // console.log(this.state.igvVenta);
    // console.log(this.state.subTotalVenta);
    // console.log(this.state.totalVenta);
    // console.log(this.state.observacionVenta);
    // console.log(this.state.productosDetalleList);
    // console.log('TOTAL DE DATOS ENVIADOS --- ');
    // LA FUNCION AGREGA UN PRODUCTO TEMPORAL
    cogoToast.loading(
        <div>
            <h4>GENERANDO VENTA INTERNA</h4>
        </div>, 
        {
            position: 'top-right'
        }
        
    )
    .then(() => {
        fetch(`/venta/guardarVentaInterna`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token'            : csrf_token,
                    'tipoDocumento'     : this.state.tipoDocumentoCliente,
                    'numeroDocumento'   : this.state.numeroDocumentoCliente,
                    'nombreCliente'     : this.state.nombreCliente,
                    'tipoComprobante'   : this.props.tipoComprobante,
                    'sucursalId'        : this.props.sucursalId,
                    'tipoMoneda'        : this.state.tipoMoneda,
                    'numeroVenta'       : this.props.numeroVenta,
                    'dateFactura'       : '2019-11-11',
                    'descuentoVenta'    : this.state.descuentoVenta,
                    'igvVenta'          : this.state.igvVenta,
                    'subTotalVenta'     : this.state.subTotalVenta,
                    'totalVenta'        : this.state.totalVenta,
                    'observacionVenta'  : this.state.observacionVenta,
                    'detallesVenta'     : this.state.productosDetalleList,


                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json',
                }
            }
        )
        .then(response => response.json())
        .then(data => {
            console.log(data);
            console.log(data['response'])
            if(data['response'] == true){

                this.props.fetchVentaDataTabla(1, '','');
                this.props.addNuevaVenta();
                this.props.fechtDataNuevaVenta();

                cogoToast.success(
                    <div>
                        <h4>VENTA REALIZADA CORRECTAMENTE</h4>
                    </div>, 
                    {
                    position: 'top-right'
                    }
                );

                if(imprimir == 1){
                    let url = `http://localhost/api/imprimir/venta/`+data['idVenta'];
                    cogoToast.loading(
                        <div>
                            <h4>IMPRIMIENDO VENTA</h4>
                        </div>, 
                        {
                            position: 'top-right'
                        }
                        
                    )
                    .then(() => {
                        fetch(
                            url
                        )
                        .then(
                            res => res.json()
                        )
                        .then(
                            data => {
                                if(data['respuesta']){
                                    cogoToast.success(
                                        <div>
                                            <h4>COPIA DE VENTA FINALIZADA</h4>
                                        </div>, 
                                        {
                                        position: 'top-right'
                                        }
                                    );
                                }else{
                                    cogoToast.error(
                                        <div>
                                            <h4>NO SE PUDO CONECTAR CON LA IMPRESORA</h4>
                                        </div>, 
                                        {
                                        position: 'top-right'
                                        }
                                    );
                                }
                            }
                        )
                    });
                }
                

            }else{
                cogoToast.error(
                    <div>
                        <h4>HUBO UN PROBLEMA AL MOMENTO DE REALIZAR LA VENTA</h4>
                    </div>, 
                    {
                    position: 'top-right'
                    }
                );
            }
            
        })
    });
    
}




render(){
    return(
        
<div className="col-lg-12 grid-margin stretch-card">
    <div className="card card-default">
        <div className="card-body"> 
            <h6 className="card-title">VENTA INTERNA:</h6>
            <form method="post" role="form" data-toggle="validator" id="frm_emitirBoleta">
                <div className="form-group" >
                    <div className="row">
                        <div className="col-3">
                            <label>  <i className="mdi mdi-account-card-details"></i>   Metodo de pago </label>
                            <div className="input-group">
                                <select 
                                    className="form-control" 
                                    name="tipoPago" 
                                    id="tipoPago" 
                                    style={{width: '100%'}} 
                                    onChange={this.cambiarTipoPago}>
                                    {/* ID DEL TIPO DE MONEDA */}
                                    <option value="1" > EFECTIVO   </option>
                                    <option value="3" > TARJETA   </option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <h6 className="card-title">CLIENTE:</h6>
                <div className="form-group" >
                    <div className="row">
                        <div className="col-3">
                            <label>  <i className="mdi mdi-account-card-details"></i>   Tipo de Documento: *</label>
                            <div className="input-group">
                                <select 
                                    className="form-control" 
                                    name="tipoDocumento" 
                                    id="tipoDocumento" 
                                    style={{width: '100%'}} 
                                    onChange={this.cambiarTipoDocumento}>
                                    {/* CODIGO DEL TIPO DE COMPROBANTE EN VEZ DE ID */}
                                    <option value="1" > DNI   </option>
                                    <option value="6" > RUC   </option>
                                    <option value="0" > OTROS </option>

                                </select>
                            </div>
                        </div>
                        {
                            this.state.tipoDocumentoCliente != 0
                            ?<div className="col-9">
                                <div className="row">
                                    <div className="col-3">
                                        <label>N° de Documento: *</label>
                                        <input type="number" 
                                            className   = "form-control" 
                                            name        = "numeroDocumento" 
                                            id          = "numeroDocumento" 
                                            onChange    = {this.getCambioNumeroDocumento}
                                            value       = {this.state.numeroDocumentoCliente}
                                            autoFocus

                                            />
                                    </div>
                                    <div className="col-5">
                                        <label>Nombre del cliente: *</label>
                                        <input 
                                            type        = "text" 
                                            className   = "form-control" 
                                            name        = "nombreCliente" 
                                            id          = "nombreCliente"
                                            value       = {this.state.nombreCliente}
                                            onChange    = {this.getCambioNombreCliente}
                                            />
                                    </div>
                                    <div className="col-2">
                                        <label>N° Ventas </label><br/>
                                        <span>{this.state.numeroVentasRealizadas}</span>
                                    </div>
                                    <div className="col-2">
                                        <label>Total de ventas </label><br/>
                                        <span>S/{this.state.totalVentasRealizadas}</span>
                                    </div>
                                </div>
                            </div>
                            :null
                        }
                        
                    </div>
                </div>
                <h6 className="card-title">DETALLE DOCUMENTO:</h6>
                <div className="row">
                    <div className="col-12">
                        <table className="table table-bordered" id="tbl_products">
                            <thead>
                                <tr>
                                    <th width="20%;">Codigo</th>
                                    <th width="25%;">Producto</th>
                                    <th  width="10%;">Cantidad</th>
                                    <th width="2%;">Disponible</th>
                                    <th width="7%;">Precio</th>
                                    <th width="7%;">Descuento</th>
                                    <th>SubTotal</th>
                                    <th>Total</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {/* {
                                    this.state.arrayProductosDetalle
                                    ?this.state.arrayProductosDetalle.map((data)=>{
                                       return(
                                        <ProductoAddDetalleComponent key={data} number={data} eliminarProductoDetalle={this.eliminarProductoDetalle} buscarCodigoProducto={this.buscarCodigoProducto} />
                                       ) 
                                    })
                                    :null 
                                } */}
                                {
                                    
                                    this.state.productosDetalleList
                                    ?this.state.productosDetalleList.map((data, posicion)=>{
                                       return(
                                            <ProductoAddDetalleComponent 
                                                key                             ={data.idLista} 
                                                numero                          ={data.idLista}
                                                posicion                        ={posicion}
                                                codigo                          ={data.codigoProducto}
                                                producto                        ={data.nombreProducto}
                                                stock                           ={data.stockProducto}
                                                precio                          ={data.precioProducto}
                                                descuento                       ={data.totalDescuento}
                                                subTotal                        ={data.subTotalProducto}
                                                total                           ={data.totalProducto}
                                                descuentoAplicado               ={data.descuentoAplicado}
                                                nuevoPrecioOferta               ={data.nuevoPrecioOferta}
                                                calcularSubTotalTotalDetalle    ={this.calcularSubTotalTotalDetalle}
                                                eliminarProductoDetalle         ={this.eliminarProductoDetalle} 
                                                buscarCodigoProducto            ={this.buscarCodigoProducto}
                                                cambiarCodigo                   ={this.cambiarCodigo}
                                            />
                                       ) 
                                    })
                                    :null
                                }
                            </tbody>
                        </table>
                    </div>
                    
                    <div className="col-12">
                        <br/>
                        {
                            this.state.cantidadProductosDetalle == 0
                            ?<button type="button" 
                                className="btn btn-success" id="btnAddProduct" onClick={()=>this.agregarProducto()} >
                                <i className="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO AL DETALLE
                            </button>

                            :<button type="button" 
                                className="btn btn-warning" id="btnAddProduct" onClick={()=>this.agregarProducto(this.state.cantidadProductosDetalle - 1)} >
                                <i className="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO TEMPORAL
                            </button>
                        }
                        {/* <button type="button" 
                            className="btn btn-warning" onClick={()=>this.verArray()} >
                            <i className="mdi mdi-plus-circle"></i>   Ver array
                        </button> */}
                        {
                            this.state.cantidadProductosDetalle != 0
                            ?<button type="button" 
                                className="btn btn-warning" onClick={() => this.modalBuscarProducto()} >
                                <i className="mdi mdi-plus-circle"></i>   Buscar producto
                            </button>
                            :null
                        }
                        <br/>
                    </div>
                </div><br/>
                <div className="col-md-12" style={{marginTop: '15px'}}>
                    <div className="row">
                        <div className="col-sm-7">
                            <div className="content-group">
                                <h6>Observación:</h6>
                                <textarea 
                                    className="form-control" 
                                    name="observacionVenta" 
                                    id="exampleTextarea1" 
                                    rows="8"
                                    onChange={this.getCambioObservacion}
                                    >{this.state.observacionVenta}</textarea>
                            </div>
                        </div>
                        <div className="col-sm-5">
                            <div className="content-group">
                                <h6>Resumen:</h6>
                                <div className="table-responsive no-border">
                                    <table className="table">
                                        <tbody>
                                            {/* <tr>
                                                <th>Descuento realizado:</th>
                                                <td className="text-right">
                                                    {/* S/. <span id="descuentoVentaTexto">{this.state.descuentoVenta}</span> 
                                                    <input 
                                                        type        = "text" 
                                                        name        = "descuentoVenta"
                                                        className   = "form-control"
                                                        id          = "descuentoVenta" 
                                                        onChange    = {this.getCambioDescuentoVenta}
                                                        onKeyDown   = {this.getCambioDescuentoVenta}
                                                        value       = {this.state.descuentoVentaRealizado}/>
                                                </td>
                                            </tr> */}
                                            <tr>
                                                <th>Descuento Total:</th>
                                                <td className="text-right">
                                                    S/. <span id="descuentoVentaTexto">{this.state.descuentoVenta}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal:</th>
                                                <td className="text-right">
                                                    S/. <span id="subTotalVentaTexto">{this.state.subTotalVenta}</span>
                                                    <input type="hidden" name="subTotalVenta" id="subTotalVenta" value="0"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>IGV: <span className="text-regular">(18%)</span></th>
                                                <td className="text-right">
                                                    S/. <span id="igvVentaTexto">{this.state.igvVenta}</span>
                                                    <input type="hidden" name="igvVenta" id="igvVenta" value="0"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td className="text-right text-primary"><h5 className="text-semibold">
                                                    S/. <span id="totalVentaTexto">{this.state.totalVenta}</span></h5>
                                                    <input type="hidden" name="totalVenta" id="totalVenta" value="2"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div className="form-group boton">
                    <div className="row">
                        {/* <div className="col-6">
                            <button type="button" 
                                className=" addexis form-control btn btn-block btn-primary btn-lg" 
                                id="emitirBoleta">
                                EMITIR BOLETA ELECTRÓNICA</button>
                        </div> */}
                        <div className="col-12">
                            <button type="button" 
                                className=" addexis form-control btn btn-block btn-success btn-lg"
                                onClick={()=>this.generarVentaInterna()}
                                
                                >
                                Generar Venta Interna</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>

    <Modal
        size="xl"
        show={this.state.estadoa}
        onHide={() => this.modalBuscarProducto()}            
    >
    <Modal.Header closeButton>
        <Modal.Title id="example-custom-modal-styling-title">
            Buscar producto especifico
        </Modal.Title>
    </Modal.Header>
    <Modal.Body>
        <div className="card card-default">
                    <div className="card-header cabezera">
                        <h4> Buscar producto especifico </h4>
                    </div>
                    <div className="modal-body">
                        
                            <div className="card">
                                <div className="card-body">
                                    <h4 className="card-title">Buscar</h4>
                                    <div className="row">
                                        <div className="col-3">
                                            <label>Buscar Codigo</label>
                                            <input 
                                                type="text" 
                                                className="form-control form-control-lg" 
                                                name="bcodigo" id="buscar_tb_codigo"
                                                onChange={this.handleChangeBuscador}
                                            />
                                        </div>
                                        <div className="col-2">
                                            <label>Buscar Marca</label>
                                            <input 
                                                type="text" 
                                                className="form-control form-control-lg" 
                                                name="bmarca" 
                                                id="buscar_tb_marca"
                                                onChange={this.handleChangeBuscador}
                                            />
                                        </div>
                                        <div className="col-2">
                                            <label>Buscar Tipo</label>
                                            <input 
                                                type="text" 
                                                className="form-control form-control-lg" 
                                                name="btipo" 
                                                id="buscar_tb_tipo"
                                                onChange={this.handleChangeBuscador}
                                            />
                                        </div>
                                        <div className="col-3">
                                            <label>Buscar Nombre</label>
                                            <input 
                                                type="text" 
                                                className="form-control form-control-lg" 
                                                name="bnombre" 
                                                id="buscar_tb_nombre"
                                                onChange={this.handleChangeBuscador}
                                            />
                                        </div>
                                        <div className="col-2">
                                            <label>Buscar Precio</label>
                                            <input 
                                                type="text" 
                                                className="form-control form-control-lg" 
                                                name="bprecio" 
                                                id="buscar_tb_precio"
                                                onChange={this.handleChangeBuscador}
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        <div className="card-body" id="agregarProductoDetalleModalBody">
                            <table className="table table-bordered dataTables_length" id="tb_buscarProducto" style={{width:'100%'}}>
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Marca</th>
                                        <th>Tipo</th>
                                        <th>Disponibles</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Seleccionar</th>
                                    </tr>
                                    {
                                        this.state.productos_tb.data 
                                        ?this.state.productos_tb.data.map((task, posicion) =>{
                                            return (
                                                <tr key={task.idVentas}>
                                                    <td>{task.codigoProducto}</td>
                                                    <td>{task.marcaProducto}</td>
                                                    <td>{task.tipoProducto}</td>
                                                    <td>{task.disponiblesProducto}</td>
                                                    <td>{task.nombreProducto}</td>
                                                    <td>{task.precioProducto}</td>
                                                    <td>
                                                    <button 
                                                        className="btn btn-sm btn-primary ver" 
                                                        type="button" 
                                                        onClick={()=>this.seleccionarProductoTb(task.codigoProducto)}
                                                       >
                                                        <i className="mdi mdi-send"></i></button>
                                                    </td>
                                                </tr>
                                            );
                                        })   
                                        : null
                                      }
                                </thead>
                            </table>  
                            <div className="container">
                                <div className="row justify-content-end">
                                    <div className="col-4">
                                        {
                                            this.state.estadoBoton
                                            ?this.state.estadoBoton.map(task =>{
                                                return (
                                                    <button key={task}
                                                        className="btn btn-sm btn-secondary editar" type="button" onClick={()=>this.cambiarPagina(task)}>{task}</button>

                                                );
                                            })   
                                            : null
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    </Modal.Body>
    </Modal>
    
    <Toast
        style={{
            position        : 'fixed',
            top             : 20,
            right           : 20,
            zIndex          :1060,
            backgroundColor : this.state.colorToast,
            color           :'white'
        }}
        onClose={() => this.setState({
            estadoToast: false
        })}
        show    ={this.state.estadoToast}
        delay   ={2500}
        autohide
        >
        <Toast.Body>
        <h3>{this.state.mensajeToast}</h3>
        </Toast.Body>
    </Toast>

    
</div>

)}}

export default NuevaVenta