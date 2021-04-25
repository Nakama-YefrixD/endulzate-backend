import React from 'react'
import {Component} from 'react';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import cogoToast from 'cogo-toast';

class EliminarVenta extends Component {
    
    constructor(){
        super();
        this.state ={
            txt_motivoCancelar  : '',
            colorToastDanger    : 'rgba(205,55,55,0.7)',
            colorToastSuccess   : 'rgba(76,208,76,0.7)'

        }

        this.confirmarEliminarVenta        = this.confirmarEliminarVenta.bind(this);
        this.getCambioMotivoCancelar       = this.getCambioMotivoCancelar.bind(this);
        this.fetchCancelarVenta            = this.fetchCancelarVenta.bind(this);
    }

    confirmarEliminarVenta(){
        confirmAlert({
            customUI: ({ onClose }) => {
                return (
                    <div className="page-content container-fluid">
                        <div className="row">
                            <div className="col-12">
                                <div className='card'>
                                    <div className="card-body">
                                        <h1>Cancelar Venta</h1>
                                        <p>Recuerda que al cancelar una venta, los productos en esta regresan a su respectivo almacen</p>
                                        <div className="form-group boton">
                                            <div className="row">
                                                <div className="col-12">
                                                    <label>Motivo de cancelar la venta</label>
                                                    <textarea 
                                                        className   = "form-control" 
                                                        rows        = "3" 
                                                        placeholder = "Motivo de cancelar la venta"
                                                        onChange    = {(e)=>this.getCambioMotivoCancelar(e)}
                                                        name        = "txt_motivoCancelar"
                                                    >
                                                    </textarea>
                                                </div>
                                            </div><br></br>
                                            
                                            <div className="row">
                                                <div className="col-6">
                                                    <button 
                                                        type="button"
                                                        onClick={() => {
                                                            this.fetchCancelarVenta();
                                                            onClose();
                                                        }}
                                                        className="btn waves-effect waves-light btn-block btn-danger">
                                                            CANCELAR VENTA
                                                    </button>
                                                </div>
                                                <div className="col-6">
                                                    <button 
                                                        type="button" 
                                                        onClick={() => {
                                                            onClose();
                                                        }}
                                                        className="btn waves-effect waves-light btn-block btn-info">
                                                            SALIR
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

    getCambioMotivoCancelar(e){
        this.setState({[e.target.name]: e.target.value})
    }

    fetchCancelarVenta(){
        let url = `/ventas/cancelar/venta/interna`;

        cogoToast.loading(
            <div>
                <h4>CANCELANDO VENTA</h4>
            </div>, 
            {
                position: 'top-right'
            }
            
        )
        .then(() => {
            fetch(url,
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token' : csrf_token,
                        'id'     : this.props.idVenta,
                        'motivo' : this.state.txt_motivoCancelar
                    }),
                    headers: {
                        'Accept' : 'application/json',
                        'Content-Type': 'application/json',
                    }
                }
            )
            .then(response => response.json())
            .then(data => {
    
                if(data['respuesta'] == true){
                    this.setState({
                        txt_motivoCancelar  : '',
                    })
                    cogoToast.success(
                        <div>
                            <h4>VENTA CANCELADA CORRECTAMENTE</h4>
                        </div>, 
                        {
                          position: 'top-right'
                        }
                    );
                    this.props.fetchVentaDataTabla(1, '', '');
    
                }else{
                    cogoToast.error(
                        <div>
                            <h4>HUBO UN PROBLEMA AL CANCELAR LA VENTA</h4>
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
            <button 
                style       = {{"margin-right":"1em"}}
                type        = "button" 
                id          = "btn_venta"
                onClick     = {this.confirmarEliminarVenta}
                className   = "btn btn-danger btn-rounded btn-fw">
                    <i className="mdi mdi-delete"></i>
            </button>
         ) 
    }
}

export default EliminarVenta