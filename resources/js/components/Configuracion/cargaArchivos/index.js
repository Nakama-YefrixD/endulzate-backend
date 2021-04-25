import React, { Component } from 'react'
// import TB_Descuentos from './tabla/tb_descuentos'

class CargaArchivos extends Component {
    constructor(){
        super();
        this.state ={

            //modal
            estadoModalAgregarOferta    : false,
            // DATOS DEL MODAL
            idProductoEscaneado         : 0,
            codigoProductoEscaneado     : '',
            nombreProductoEscaneado     : '',
            stockProductoEscaneado      : 0,
            precioProductoEscaneado     : 0,
            cantidadOferta              : '',
            nuevoPrecioOferta           : '',

            // TOAST
            estadoToast     : false,
            mensajeToast    : '',
            colorToast      : 'rgba(76,208,76,0.7)',


        }
        this.getCambioCodigo            = this.getCambioCodigo.bind(this);
        this.fetchCodigoProducto        = this.fetchCodigoProducto.bind(this);
        this.getCambioCantidadOferta    = this.getCambioCantidadOferta.bind(this);
        this.getCambioNuevoPrecio       = this.getCambioNuevoPrecio.bind(this);
        this.fetchAgregarOferta         = this.fetchAgregarOferta.bind(this);
    }

    modalAgregarOferta() {
        this.setState({
            estadoModalAgregarOferta: !this.state.estadoModalAgregarOferta
        });
    }

    activarToast(message, color) {
        this.setState({
            mensajeToast : message,
            colorToast   : color,
            estadoToast  : true
        });
    }

    getCambioCodigo(e)
    {
        const {name , value} = e.target;
        this.setState({
            codigoProductoEscaneado : value
        })
        if (e.key === 'Enter') {
            console.log(value);
            this.fetchCodigoProducto(value);
        }

    }

    fetchCodigoProducto(codigo){ 
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
               
                var producto = data['producto'];
                this.setState({
                    idProductoEscaneado     : producto['id'],
                    nombreProductoEscaneado : producto['nombre'],
                    stockProductoEscaneado  : producto['cantidad'],
                    precioProductoEscaneado : producto['precio'],
                })


            }else{
                this.activarToast("Producto no encontrado", "rgba(205,55,55,0.7)");
            }
            
        })
    }

    getCambioCantidadOferta(e)
    {
        const {name , value} = e.target;
        this.setState({
            cantidadOferta : value
        })
    }
    
    getCambioNuevoPrecio(e)
    {
        const {name , value} = e.target;
        this.setState({
            nuevoPrecioOferta : value
        })
    }

    fetchAgregarOferta()
    {
        fetch(`/configuraciones/descuentos/crear`,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token'            : csrf_token,
                'idProducto'        : this.state.idProductoEscaneado,
                'precioProducto'    : this.state.precioProductoEscaneado,
                'nuevoPrecio'       : this.state.nuevoPrecioOferta,
                'cantidad'          : this.state.cantidadOferta,
            }),
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            console.log(data['response'])
            if(data['response'] == true){
                this.activarToast("Oferta creada satisfactoriamente", "rgba(76,208,76,0.7)");
                this.modalAgregarOferta();
            }else{
                this.activarToast("Ocurrio un error al momento de agregar la oferta", "rgba(205,55,55,0.7)");
            }
            
        })
    }
    

    render(){
        return(
            <div className="row">
                <div className="col-lg-12 grid-margin stretch-card">
                    <div className="card">
                        <div className="card-body">
                            <h4 className="card-title">Acciones</h4>
                            <button type="button"
                                onClick={()=>this.modalAgregarOferta()}
                                className="btn btn-primary btn-rounded btn-fw">Crear una oferta</button>
                        </div>
                    </div>
                </div>

                {/* <TB_Descuentos 

                /> */}
            
            </div>
        )
        
    }
}

export default CargaArchivos
