import React from 'react'
import { Link } from 'react-router-dom'
import {Component} from 'react';

class VentaBoleta extends Component {
    constructor(){
        super();
        this.state ={
        }

}

render(){
    return(
        <div className="col-lg-12 grid-margin stretch-card">
        <div className="card card-default">
            <div className="card-body">
             <h6 className="card-title">BOLETA ELECTRÓNICA:</h6>
            <form method="post" role="form" data-toggle="validator" id="frm_emitirBoleta">
                <div className="form-group">
                    <div className="row">
                        <div className="col-3">
                            <label><i className="mdi mdi-barcode"></i>   Serie:</label>
                            <input type="hidden" name="tipoComprobante" id="tipoComprobante" value="{{ $tiposcomprobante->id }}" className="form-control" readonly="readonly" />
                            <input type="text" name="serieVenta" id="serieVenta" value="{{ $tiposcomprobante->serie }}" className="form-control" readonly="readonly"/>
                        </div>
                        <div className="col-3">
                            <label> <i className="mdi mdi-file-document-box"></i>    Nº Boleta:</label>
                            <input type="number" className="form-control" name="facturaVenta" id="facturaVenta" value="{{ $tiposcomprobante->correlativo }}"/>
                        </div>
                        <div className="col-3">
                            <label> <i className="mdi mdi-calendar-text"></i>    Fecha de emisión:</label>
                            <input type="text" className="form-control" value ="{{ $fechaActual }}" name="dateFactura" id="dateFactura"/>
                        </div>
                        <div className="col-3">
                            <label> <i className="mdi mdi-cash-multiple"></i>  Moneda:</label>
                            <div className="input-group">
                                <select className="form-control" name="tipoMoneda" id="tipoMoneda" style={{width: '100%'}}>
                                    @foreach($tiposMoneda as $tipoMoneda)
                                        <option value="{{ $tipoMoneda->id }}" > tipoMoneda->nombre  </option>
                                    @endforeach
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
                                <select className="form-control" name="tipoDocumento" id="tipoDocumento" style={{width: '100%'}}>
                                    <option value="1" > DNI </option>
                                </select>
                            </div>
                        </div>
                        <div className="col-3">
                            <label>N° de Documento: *</label>
                            <input type="number" className="form-control" name="numeroDocumento" id="numeroDocumento"/>
                        </div>
                        <div className="col-6">
                            <label>Nombre del cliente: *</label>
                            <input type="text" className="form-control" name="nombreCliente" id="nombreCliente"/>
                        </div>
                    </div>
                </div>
                <h6 className="card-title">DETALLE DOCUMENTO:</h6>
                <div className="row">
                    <div className="col-12">
                        <table className="table table-bordered" id="tbl_products">
                            <thead>
                                <tr>
                                    <th width="25%;">Producto</th>
                                    <th>Cantidad</th>
                                    <th width="2%;">Disponible</th>
                                    <th width="25%;">Precio</th>
                                    <th>Descuento</th>
                                    <th>SubTotal</th>
                                    <th>Total</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select className="form-control productos " name="nombreProducto[]" id="nombreProducto" style={{width: '100%'}}>
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" 
                                                precio="{{ $producto->precio }}"  disponible="{{ $producto->cantidad }}"> $producto->nombre </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td className="cantidad">
                                        <input type="text" className="form-control c_quantity" name="cantidad[]"  value="0"/>
                                    </td>
                                    <td className="disponible">
                                        <span>0</span>
                                        <input type="hidden" className="form-control disponible" name="disponible[]" value="0" readonly=""/>
                                    </td>
                                    <td className="precio">
                                        <span>0</span>
                                        <input type="hidden" className="form-control precio" name="precio[]" value="0" readonly="" style={{width: '100%'}}/>
                                    </td>
                                    <td>
                                        <span>0</span>
                                        <input type="hidden" className="form-control descuento" name="descuento[]" value="0" readonly=""/>
                                    </td>
                                    <td className="subtotal">
                                        <span>0</span>
                                        <input type="hidden" className="form-control subtotal" name="subtotal[]" value="0" readonly="" style={{width: '100%'}}/>
                                    </td>
                                    <td className="total">
                                        <span>0</span>
                                        <input type="hidden" className="form-control total" name="total[]" value="0" readonly="" style={{width: '100%'}}/>
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div className="col-12">
                        <br/>
                        
                        <button type="button" className="btn btn-warning" id="btnAddProduct">
                            <i className="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO AL DETALLE
                        </button>
                        <br/>
                    </div>
                </div><br/>
                <div className="col-md-12" style={{marginTop: '15px'}}>
                    <div className="row">
                        <div className="col-sm-7">
                            <div className="content-group">
                                <h6>Observación:</h6>
                                <textarea className="form-control" name="observacionVenta" id="exampleTextarea1" rows="8" placeholder="Escribe aquí una observación">SN</textarea>
                            </div>
                        </div>
                        <div className="col-sm-5">
                            <div className="content-group">
                                <h6>Resumen:</h6>
                                <div className="table-responsive no-border">
                                    <table className="table">
                                        <tbody>
                                            <tr>
                                                <th>Descuento:</th>
                                                <td className="text-right">
                                                    S/. <span id="descuentoVentaTexto">0</span>
                                                    <input type="hidden" name="descuentoVenta" id="descuentoVenta" value="0"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal:</th>
                                                <td className="text-right">
                                                    S/. <span id="subTotalVentaTexto">0</span>
                                                    <input type="hidden" name="subTotalVenta" id="subTotalVenta" value="0"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>IGV: <span className="text-regular">(18%)</span></th>
                                                <td className="text-right">
                                                    S/. <span id="igvVentaTexto">0</span>
                                                    <input type="hidden" name="igvVenta" id="igvVenta" value="0"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td className="text-right text-primary"><h5 className="text-semibold">
                                                    S/. <span id="totalVentaTexto">0</span></h5>
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
                        <div className="col-6">
                            <button type="button" className=" addexis form-control btn btn-block btn-primary btn-lg" id="emitirBoleta">
                                EMITIR BOLETA ELECTRÓNICA</button>
                        </div>
                        <div className="col-6">
                            <button type="button" className=" addexis form-control btn btn-block btn-success btn-lg" id="guardarBoleta">
                                GUARDAR BOLETA ELECTRÓNICA</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
        </div>
        </div>

)}}

export default VentaBoleta