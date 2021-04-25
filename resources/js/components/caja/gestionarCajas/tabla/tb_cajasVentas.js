import React from 'react'
import {Component} from 'react';
import TB_filas_cajasVentas from './tb_filas_cajasVentas';
import agregarBotones from '../../../extras/agregarBotones'

class TB_CajasVentas extends Component {
    
    constructor(){
        super();
        this.state ={
            tb_cajaVenta    : [],

            estadoBoton     : []           


        }
        this.fetchCajasVentas  = this.fetchCajasVentas.bind(this);
    }

    componentDidMount(){
        this.fetchCajasVentas(1, '', '');
    }

    fetchCajasVentas(number, cliente, numeroComprobante){
        let url = `/cierrescajas/tb_cierreCaja?page=${number}&bcliente=${cliente}&bnumeroComprobante=${numeroComprobante}`;
        fetch(
            url
        )
        .then(
            res => res.json()
        )
        .then(
            data => {

                this.setState({
                    tb_cajaVenta    : data['tb_cajaVenta'].data,
                    numeroPagina    : number
                },()=>{
                    
                    this.setState({
                        estadoBoton : agregarBotones(data['tb_cajaVenta'].last_page)
                    })
                });
            }
        )
    }

    



    render(){
        return(
            <div>
                <h6 className="card-title">REGISTRO DE CUADRE DE CAJA:</h6>
                <div className="row">
                    <div className="col-12">
                        <div className="table-responsive">
                            <table className="table table-bordered table-striped" id="tbl_products">
                                <thead>
                                    <tr>
                                        <th>OPCIONES</th>
                                        <th>SUCURSAL</th>
                                        <th>USUARIO</th>
                                        <th>ESTADO</th>
                                        <th>NUMERO</th>
                                        <th>FECHA APERTURA</th>
                                        <th>FECHA CIERRE</th>
                                        <th>APERTURA (S/)</th>
                                        <th>APERTURO (S/)</th>
                                        <th>CIERRE (S/)</th>
                                        <th>CERRO (S/)</th>
                                        <th>N° TAJERTA </th>
                                        <th>TARJETA (S/)</th>
                                        <th>N° EFECTIVO </th>
                                        <th>EFECTIVO (S/)</th>
                                        <th>N° CANCELADOS</th>
                                        <th>CANCELADOS (S/)</th>
                                        <th>N° GASTOS</th>
                                        <th>GASTOS (S/)</th>
                                        <th>N° INGRESOS</th>
                                        <th>INGRESOS (S/)</th>
                                        <th>N° VENTAS</th>
                                        <th>VENTAS (S/) </th>
                                        <th>N° ITEMS VENDIDOS</th>
                                        <th>N° ITEMS CANCELADOS</th>
                                        <th>OBSERVACIONES APERTURA </th>
                                        <th>OBSERVACIONES CIERRE </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.state.tb_cajaVenta
                                        ?this.state.tb_cajaVenta.map((data, posicion)=>{
                                            return(
                                                    <TB_filas_cajasVentas 
                                                        key                             =   {data.idCajaVenta}
                                                        nombreUsuario                   =   {data.nombreUsuario}
                                                        nombreSurcursal                 =   {data.nombreSurcursal}
                                                        cierreCajaVenta                 =   {data.cierreCajaVenta}
                                                        numeroCajaVenta                 =   {data.numeroCajaVenta}
                                                        aperturaCajaVenta               =   {data.aperturaCajaVenta}
                                                        cierreCajaVenta                 =   {data.cierreCajaVenta}
                                                        totalAperturaCajaVenta          =   {(data.totalAperturaCajaVenta * 1).toFixed(2)}
                                                        totalAperturoCajaVenta          =   {(data.totalAperturoCajaVenta * 1).toFixed(2)}
                                                        totalCierreCajaVenta            =   {(data.totalCierreCajaVenta * 1).toFixed(2)}
                                                        totalCerroCajaVenta             =   {(data.totalCerroCajaVenta * 1).toFixed(2)}
                                                        numeroVentasTarjetaCajaVenta    =   {data.numeroVentasTarjetaCajaVenta}
                                                        totalVentasTarjetaCajaVenta     =   {(data.totalVentasTarjetaCajaVenta * 1).toFixed(2)}
                                                        numeroVentasEfectivoCajaVenta   =   {data.numeroVentasEfectivoCajaVenta}
                                                        totalVentasEfectivoCajaVenta    =   {(data.totalVentasEfectivoCajaVenta * 1).toFixed(2)}
                                                        numeroVentasCanceladasCajaVenta =   {data.numeroVentasCanceladasCajaVenta}
                                                        totalVentasCanceladasCajaVenta  =   {(data.totalVentasCanceladasCajaVenta * 1).toFixed(2)}
                                                        numeroGastosCajaVenta           =   {data.numeroGastosCajaVenta}
                                                        totalGastosCajaVenta            =   {(data.totalGastosCajaVenta * 1).toFixed(2)}
                                                        numeroIngresosCajaVenta         =   {data.numeroIngresosCajaVenta}
                                                        totalIngresosCajaVenta          =   {(data.totalIngresosCajaVenta * 1).toFixed(2)}
                                                        numeroVentasCajaVenta           =   {data.numeroVentasCajaVenta}
                                                        totalVentasCajaVenta            =   {(data.totalVentasCajaVenta * 1).toFixed(2)}
                                                        numeroItemsCajaVenta            =   {data.numeroItemsCajaVenta}
                                                        numeroItemsCanceladosCajaVenta  =   {data.numeroItemsCanceladosCajaVenta}
                                                        observacionesAperturaCajaVenta  =   {data.observacionesAperturaCajaVenta}
                                                        observacionesCierreCajaVenta    =   {data.observacionesCierreCajaVenta}
                                                        
                                                    />
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
                                                    className   = "btn btn-sm btn-secondary editar" 
                                                    type        = "button" 
                                                    onClick     = {()=>this.fetchCajasVentas(task, "", "")}>{task}</button>

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
         ) 
    }
}

export default TB_CajasVentas