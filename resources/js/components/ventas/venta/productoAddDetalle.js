import React from 'react'
import {Component} from 'react';


class ProductoAddDetalleComponent extends Component {
    
    constructor(){
        super();
        this.state ={
            cantidad : 1

        }
        this.handleChange = this.handleChange.bind(this);
        this.calcularSubTotalTotal = this.calcularSubTotalTotal.bind(this);
    }

    handleChange (e){
        const {name , value} = e.target;

        if (e.key === 'Enter') {
            console.log(value);
            this.props.buscarCodigoProducto(value, this.props.posicion);
        }
    }

    calcularSubTotalTotal(e){
        this.setState({
            cantidad : value
        })
        const {name , value} = e.target;
        console.log(value);
        this.props.calcularSubTotalTotalDetalle(this.props.posicion, value, this.props.precio);
    }

    render(){
        return(
            <tr>
                <td>
                    <input 
                        type="text" 
                        className="form-control " 
                        name="codigo[]" 
                        autoFocus
                        value={this.props.codigo}
                        onChange={e => this.props.cambiarCodigo(e, this.props.posicion)}
                        onKeyDown={this.handleChange}/>
                </td>
                <td>
                    <span>{this.props.producto}</span>
                    {/* <input 
                        type="text" 
                        className="form-control"
                        value={this.props.producto}
                        name="producto[]" /> */}
                </td>
                <td className="cantidad">
                    <input 
                        type="number" 
                        className="form-control c_quantity"
                        onChange={this.calcularSubTotalTotal}
                        value={this.state.cantidad}
                        name="cantidad[]" />
                </td>
                <td className="disponible">
                    <span>{this.props.stock}</span>
                    <input type="hidden" className="form-control disponible" name="disponible[]" value="0" />
                </td>
                <td className="precio">
                    {
                        this.props.descuentoAplicado
                        ?<span><strike>{this.props.precio}</strike> - {this.props.nuevoPrecioOferta}</span>
                        :<span>{this.props.precio}</span>
                    }
                    <input type="hidden" className="form-control precio" name="precio[]" value="0" style={{width: '100%'}}/>
                </td>
                <td className="descuento">
                    <span>{this.props.descuento}</span>
                </td>
                <td className="subtotal">
                    <span>{this.props.subTotal}</span>
                    <input type="hidden" className="form-control subtotal" name="subtotal[]" value="0" style={{width: '100%'}}/>
                </td>
                <td className="total">
                    <span>{this.props.total}</span>
                    <input type="hidden" className="form-control total" name="total[]" value="0" style={{width: '100%'}}/>
                </td>
                <td>
                    <button type="button" className="btn btn-danger btn-rounded btn-icon remove" onClick={()=>this.props.eliminarProductoDetalle(this.props.numero)} >
                        <i className="mdi mdi-close"></i>
                    </button>
                </td>
            </tr>
         ) }}

    export default ProductoAddDetalleComponent