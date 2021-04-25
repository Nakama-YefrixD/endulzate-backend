import React from 'react'
import {Component} from 'react';
import agregarBotones from '../../../extras/agregarBotones'

class TB_Ingresos extends Component {
    
    constructor(){
        super();
        this.state ={
            tb_ingresos  : [],
            estadoBoton  : []

        }
        this.fetchIngresos  = this.fetchIngresos.bind(this);
    }

    componentDidMount(){
        this.fetchIngresos(1, '', '');
    }

    fetchIngresos(number, cliente, numeroComprobante){
        let url = `/caja/ingresos/tb_ingresos?page=${number}`;
        fetch(
            url
        )
        .then(
            res => res.json()
        )
        .then(
            data => {
                this.setState({
                    tb_ingresos    : data['tb_ingresos'].data,
                    numeroPagina : number
                },()=>{
                    
                    this.setState({
                        estadoBoton : agregarBotones(data['tb_ingresos'].last_page)
                    })
                });
            }
        )
    }

    render(){
        return(
            <div className="col-lg-12 grid-margin stretch-card">
                <div className="card">
                    <div className="card-body">
                        <h6 className="card-title">REGISTRO DE INGRESOS:</h6>
                        <div className="row">
                            <div className="col-12">
                                <div className="table-responsive">
                                    <table className="table table-bordered table-striped" id="tbl_products">
                                        <thead>
                                            <tr>
                                                <th>SUCURSAL</th>
                                                <th>NUMERO DE LA CAJA</th>
                                                <th>FECHA DEL INGRESO</th>
                                                <th>NUMERO DEL INGRESO</th>
                                                <th>INGRESO</th>
                                                <th>MOTIVO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {
                                                this.state.tb_ingresos
                                                ?this.state.tb_ingresos.map((data, posicion)=>{
                                                    return(
                                                        <tr key= { data.idIngreso         }>
                                                            <td> { data.nombreSucursal    }</td>
                                                            <td> { data.numeroCajaVenta   }</td>
                                                            <td> { data.fechaIngreso      }</td>
                                                            <td> { data.numeroIngreso     }</td>
                                                            <td> { data.ingreso           }</td>
                                                            <td> { data.motivoIngreso     }</td>
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
                                                            onClick     = {()=>this.fetchIngresos(task, "", "")}>{task}</button>

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

export default TB_Ingresos