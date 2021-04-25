import React from 'react'
import {Component} from 'react';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import cogoToast from 'cogo-toast';

class TB_Transferencias extends Component {

    constructor(){
        super();
        this.state ={
            idTransferencia : 0
        }

        this.confirmarEliminarTransferencia = this.confirmarEliminarTransferencia.bind(this);
        this.fetchEliminarTransferencia     = this.fetchEliminarTransferencia.bind(this);

    }

    confirmarEliminarTransferencia(){
        confirmAlert({
            title: 'ELIMINAR TRANSFERENCIA',
            message: 'Una vez eliminado no hay forma de recuperarlo',
            buttons: [
              {
                label: 'ELIMINAR',
                onClick: this.fetchEliminarTransferencia
              },
              {
                label: 'CANCELAR',
                onClick: console.log("")
              }
            ]
        });
    }

    fetchEliminarTransferencia(){
        console.log(this.state.idTransferencia)
        let url = `/almacen/transferencias/eliminar`;
        cogoToast.loading(
            <div>
                <h4>ELIMINANDO TRANSFERENCIA</h4>
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
                        '_token'            : csrf_token,
                        'idTransferencia'   : this.state.idTransferencia
                        
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

                    this.props.fetchTransferencias(1, '', '');
    
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
            // <div className="row">
            //     <Buscadores_tb_transferencias 
                                    
            //     />

                <div className="col-lg-12 grid-margin stretch-card">
                    <div className="card">
                        <div className="card-body">
                            <h6 className="card-title">REGISTRO DE TRANSFERENCIAS:</h6>
                            <div className="row">
                                <div className="col-12">
                                    <div className="table-responsive">
                                        <table className="table table-bordered table-striped" id="tbl_products">
                                            <thead>
                                                <tr>
                                                    <th>ORIGEN</th>
                                                    <th>DESTINO</th>
                                                    <th>CODIGO PRODUCTO</th>
                                                    <th>PRODUCTO</th>
                                                    <th>CANTIDAD</th>
                                                    <th>OPCIONES</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {
                                                    this.props.tb_transferencias
                                                    ?this.props.tb_transferencias.map((data, posicion)=>{
                                                        return(
                                                            <tr key={data.idTransferencia}>
                                                                <td>
                                                                    {data.origenTransferencia}
                                                                </td>
                                                                <td>
                                                                    {data.destinoTransferencia}
                                                                </td>
                                                                <td>
                                                                    {data.codigoProducto}
                                                                </td>
                                                                <td>
                                                                    {data.nombreProducto}
                                                                </td>
                                                                <td>
                                                                    {data.cantidadTransferida}
                                                                </td>

                                                                <td>
                                                                    <div className="button-group">
                                                                        {/* <button 
                                                                            type="button" 
                                                                            class="btn waves-effect waves-light btn-rounded btn-primary"
                                                                        >
                                                                                <i className="mdi mdi-eye"></i>
                                                                        </button> */}
                                                                        
                                                                        <button 
                                                                            type        = "button" 
                                                                            id          = "btn_eliminar_transferencia"
                                                                            onClick     = {
                                                                                () => {
                                                                                    this.confirmarEliminarTransferencia(),
                                                                                    this.setState({
                                                                                        idTransferencia : data.idTransferencia
                                                                                    })
                                                                                }
                                                                            }

                                                                            className   = "btn waves-effect waves-light btn-rounded btn-danger">
                                                                                <i className="mdi mdi-delete"></i>
                                                                        </button>
                                                                    </div>
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
                                                    this.props.estadoBoton
                                                    ?this.props.estadoBoton.map(task =>{
                                                        return (
                                                            <button 
                                                                key         = {task}
                                                                className   = "btn btn-sm btn-secondary" 
                                                                type        = "button" 
                                                                onClick     = {()=>this.props.fetchTransferencias(task, "", "")}>
                                                                    {task}
                                                            </button>
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
            // </div>
            
            
         ) 
    }
}

export default TB_Transferencias