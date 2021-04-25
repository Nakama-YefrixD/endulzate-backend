import React from 'react'
import {Component} from 'react';
import agregarBotones from '../../../extras/agregarBotones'

class TB_Gastos extends Component {
    
    constructor(){
        super();
        this.state ={
            tb_gastos    : [],
            estadoBoton  : []

        }
        this.fetchGastos  = this.fetchGastos.bind(this);
    }

    componentDidMount(){
        this.fetchGastos(1, '', '');
    }

    fetchGastos(number, cliente, numeroComprobante){
        let url = `/caja/gastos/tb_gastos?page=${number}`;
        fetch(
            url
        )
        .then(
            res => res.json()
        )
        .then(
            data => {
                console.log("------------------------")
                console.log(data)
                console.log("------------------------")
                this.setState({
                    tb_gastos    : data['tb_gastos'].data,
                    numeroPagina : number
                },()=>{
                    
                    this.setState({
                        estadoBoton : agregarBotones(data['tb_gastos'].last_page)
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
                        <h6 className="card-title">REGISTRO DE GASTOS:</h6>
                        <div className="row">
                            <div className="col-12">
                                <div className="table-responsive">
                                    <table className="table table-bordered table-striped" id="tbl_products">
                                        <thead>
                                            <tr>
                                                <th>SUCURSAL</th>
                                                <th>NUMERO DE LA CAJA</th>
                                                <th>FECHA DEL GASTO</th>
                                                <th>NUMERO DEL GASTO</th>
                                                <th>GASTO</th>
                                                <th>MOTIVO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {
                                                this.state.tb_gastos
                                                ?this.state.tb_gastos.map((data, posicion)=>{
                                                    return(
                                                        <tr key={data.idGasto}>
                                                            <td>{data.nombreSucursal }</td>
                                                            <td>{data.numeroCajaVenta }</td>
                                                            <td>{data.fechaGasto }</td>
                                                            <td>{data.numeroGasto }</td>
                                                            <td>{data.gasto }</td>
                                                            <td>{data.motivoGasto }</td>
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
                                                            onClick     = {()=>this.fetchGastos(task, "", "")}>{task}</button>

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

export default TB_Gastos