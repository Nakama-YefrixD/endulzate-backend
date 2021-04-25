import React from 'react'
import {Component} from 'react';
import agregarBotones from '../../../extras/agregarBotones'
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import cogoToast from 'cogo-toast';

class TB_Descuentos extends Component {
    
    constructor(){
        super();
        this.state ={
            tb_descuentos   : [],
            estadoBoton     : [],

            idDescuento     : '',

        }
        this.fetchDescuentos        = this.fetchDescuentos.bind(this);
        this.fetchEliminarDescuento = this.fetchEliminarDescuento.bind(this);
    }

    componentDidMount(){
        this.fetchDescuentos(1, '', '');
    }

    fetchDescuentos(number, cliente, numeroComprobante){
        let url = `/configuracion/descuentos/tb_descuentos?page=${number}`;
        fetch(
            url
        )
        .then(
            res => res.json()
        )
        .then(
            data => {
                this.setState({
                    tb_descuentos    : data['tb_descuentos'].data,
                    numeroPagina : number
                },()=>{
                    
                    this.setState({
                        estadoBoton : agregarBotones(data['tb_descuentos'].last_page)
                    })
                });
            }
        )
    }


    confirmarEliminarTransferencia(){
        confirmAlert({
            title: 'ELIMINAR OFERTA',
            message: 'Una vez eliminado no hay forma de recuperarlo',
            buttons: [
              {
                label: 'ELIMINAR',
                onClick: this.fetchEliminarDescuento
              },
              {
                label: 'CANCELAR',
                onClick: console.log("")
              }
            ]
        });
    }

    fetchEliminarDescuento(){
        
        let url = `/configuraciones/descuentos/eliminar`;
        cogoToast.loading(
            <div>
                <h4>ELIMINANDO OFERTA</h4>
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
                        '_token'        : csrf_token,
                        'idDescuento'   : this.state.idDescuento
                        
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

                    cogoToast.success(
                        <div>
                            <h4>{data['mensaje']}</h4>
                        </div>, 
                        {
                          position: 'top-right'
                        }
                    );

                    this.fetchDescuentos(1, '', '');
    
                }else{
                    cogoToast.error(
                        <div>
                            <h4>{data['mensaje']}</h4>
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
                <div className="card">
                    <div className="card-body">
                        <h6 className="card-title">GESTION DE OFERTAS:</h6>
                        <div className="row">
                            <div className="col-12">
                                <div className="table-responsive">
                                    <table className="table table-bordered table-striped" id="tbl_products">
                                        <thead>
                                            <tr>
                                                <th>Codigo del Producto</th>
                                                <th>Nombre del Producto</th>
                                                <th>Cantidad</th>
                                                <th>Porcentaje</th>
                                                <th>Precio del Producto</th>
                                                <th>Nuevo precio del producto</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {
                                                this.state.tb_descuentos
                                                ?this.state.tb_descuentos.map((data, posicion)=>{
                                                    return(
                                                        <tr key= { data.idDescuentos           }>
                                                            <td> { data.codigoProductos        }</td>
                                                            <td> { data.nombreProductos        }</td>
                                                            <td> { data.cantidadDescuentos     }</td>
                                                            <td> { data.porcentajeDescuentos   }</td>
                                                            <td> { data.precioProductos        }</td>
                                                            <td> { data.nuevoPrecioDescuentos  }</td>
                                                            <td>
                                                                <button 
                                                                    className   = "btn btn-sm btn-danger " 
                                                                    type        = "button" 
                                                                    onClick     = {
                                                                        () => {
                                                                            this.confirmarEliminarTransferencia(),
                                                                            this.setState({
                                                                                idDescuento : data.idDescuentos
                                                                            })
                                                                        }
                                                                    }
                                                                >
                                                                    <i className="mdi mdi-delete"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    ) 
                                                })
                                                :null
                                            }
                                        </tbody>
                                    </table>
                                </div>
                                <br></br>
                                <div className="container">
                                    <div className="row justify-content-end">
                                        <div className="col-4">
                                            {
                                                this.state.estadoBoton
                                                ?this.state.estadoBoton.map(task =>{
                                                    return (
                                                        <button 
                                                            key         = {task}
                                                            className   = "btn btn-sm btn-secondary" 
                                                            type        = "button" 
                                                            onClick     = {()=>this.fetchDescuentos(task, "", "")}>{task}</button>

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
                </div>    
            </div>
            
         ) 
    }
}

export default TB_Descuentos