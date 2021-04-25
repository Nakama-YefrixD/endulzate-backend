import React from 'react'
import {Component} from 'react';


class TB_filas_cajasVentas extends Component {
    
    constructor(){
        super();
        this.state ={

        }
    }


    render(){
        return(
            <tr>
                <td>
                    <button 
                        className="btn btn-rounded btn-fw btn-primary" 
                        type="button" >
                            <i className="mdi mdi-eye"></i>
                    </button>
                </td>
                <td>{this.props.nombreSurcursal }</td>
                <td>{this.props.nombreUsuario }</td>
                <td>{this.props.cierreCajaVenta }</td>
                <td>{this.props.numeroCajaVenta }</td>
                <td>{this.props.aperturaCajaVenta }</td>
                <td>{this.props.cierreCajaVenta }</td>
                <td>{(this.props.totalAperturaCajaVenta) }</td>
                <td>{(this.props.totalAperturoCajaVenta) }</td>
                <td>{this.props.totalCierreCajaVenta }</td>
                <td>{this.props.totalCerroCajaVenta }</td>
                <td>{this.props.numeroVentasTarjetaCajaVenta }</td>
                <td>{this.props.totalVentasTarjetaCajaVenta }</td>
                <td>{this.props.numeroVentasEfectivoCajaVenta }</td>
                <td>{this.props.totalVentasEfectivoCajaVenta }</td>
                <td>{this.props.numeroVentasCanceladasCajaVenta }</td>
                <td>{this.props.totalVentasCanceladasCajaVenta }</td>
                <td>{this.props.numeroGastosCajaVenta }</td>
                <td>{this.props.totalGastosCajaVenta }</td>
                <td>{this.props.numeroIngresosCajaVenta }</td>
                <td>{this.props.totalIngresosCajaVenta }</td>
                <td>{this.props.numeroVentasCajaVenta }</td>
                <td>{this.props.totalVentasCajaVenta }</td>
                <td>{this.props.numeroItemsCajaVenta }</td>
                <td>{this.props.numeroItemsCanceladosCajaVenta }</td>
                <td>{this.props.observacionesAperturaCajaVenta }</td>
                <td>{this.props.observacionesCierreCajaVenta }</td>
            </tr>
         ) 
    }
}

export default TB_filas_cajasVentas