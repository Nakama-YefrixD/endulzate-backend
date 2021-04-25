import React, { Component } from 'react'
import Modal from 'react-bootstrap/Modal'
import Toast from 'react-bootstrap/Toast'
import { Link } from 'react-router-dom'
import TB_Descuentos from './tabla/tb_descuentos'

class Descuento extends Component {
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

                <TB_Descuentos 

                />
            
            {/* MODAL DE AGREGAR OFERTA */}
            <Modal
                size="lg"
                show={this.state.estadoModalAgregarOferta}
                onHide={() => this.modalAgregarOferta()}
                >
                <Modal.Header closeButton>
                    <Modal.Title id="example-custom-modal-styling-title">
                    Agregar Oferta
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div className="card card-default">
                        <div className="modal-body">
                            <div className="card-body">
                                <div className="form-group" >
                                    <div className="row">
                                        <div className="col-12">
                                            <label>Codigo</label>
                                            <input 
                                                type        = "text" 
                                                className   = "form-control" 
                                                name        = "codigoProductoEscaneado" 
                                                id          = "codigoProductoEscaneado"
                                                onChange    = {this.getCambioCodigo}
                                                onKeyDown   = {this.getCambioCodigo}
                                                autoFocus
                                                value       = {this.state.codigoProductoEscaneado} 
                                            />
                                        </div>
                                        <div className="col-12" id="alertaCodigo">
                                        </div>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <div className="row">
                                        <div className="col-8">
                                            <label>Nombre del producto</label>
                                            <h4 className="card-title"> {this.state.nombreProductoEscaneado} </h4>
                                        </div>
                                        <div className="col-2">
                                            <label>Stock</label>
                                            <h4 className="card-title"> {this.state.stockProductoEscaneado} </h4>
                                        </div>
                                        <div className="col-2">
                                            <label>Precio</label>
                                            <h4 className="card-title"> S/{this.state.precioProductoEscaneado} </h4>
                                        </div>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <div className="row">
                                        <div className="col-6">
                                            <label>Cantidad</label>
                                            <input 
                                                type        = "text" 
                                                name        = "cantidadOferta" 
                                                id          = "cantidadOferta" 
                                                className   = "form-control"
                                                onChange    = {this.getCambioCantidadOferta} 
                                                value       = {this.state.cantidadOferta}/>
                                        </div>
                                        <div className="col-6">
                                            <label>Nuevo precio</label>
                                            <input 
                                                type        = "text" 
                                                name        = "nuevoPrecioOferta" 
                                                id          = "nuevoPrecioOferta" 
                                                className   = "form-control"
                                                onChange    = {this.getCambioNuevoPrecio} 
                                                value       = {this.state.nuevoPrecioOferta}/>
                                        </div>
                                    </div>
                                </div>
                                <div className="form-group boton">
                                    <button 
                                        type="button" 
                                        className="addexis form-control btn btn-block btn-success btn-lg" 
                                        id="crearProducto" 
                                        onClick={()=>this.fetchAgregarOferta()}>
                                        Agregar</button>
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
        )
        
    }
}

export default Descuento
