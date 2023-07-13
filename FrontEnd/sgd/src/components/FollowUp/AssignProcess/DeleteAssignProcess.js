import { React, useEffect, useState, useContext } from 'react'
import { fetchJson } from '../../../hooks/useFetchJson';
import Modal from '../../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../../Context/AuthContext';


export const DeleteAssignProcess = ({ selectedDataRow, closeModalDelete }) => {

    const { loginData } = useContext(AuthContext);

    const handleUpdate = async () => {
        try {
            const requestData = {
                _process: selectedDataRow.process_id,
                _article: selectedDataRow.article_id
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiArticleProcess/apiArticleProcess.php/deleteArticleProcess',
                requestData
            );
            // Realiza cualquier acción adicional con la respuesta de la API

            closeModalDelete();
        } catch (error) {
            console.error(error);
            // Maneja el error de la manera que prefieras
        }
    };

    return (
        <Modal
            title={`Eliminar asignacion de proceso en articulo: ${selectedDataRow.article_name}`}
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Eliminacion de asignacion de proceso</h3>
                    </div>
                    <div className="card-body ">
                        <div className="card-body" style={{ display: 'block' }}>
                            Esta seguro que desea borrar la asignacion de:<br /><br />
                            <strong>Proceso:</strong> {selectedDataRow.process_name}<br />
                            <strong>Articulo:</strong> {selectedDataRow.article_name}
                           
                        </div>
                    </div>
                </div>
            } // Aquí puedes pasar el contenido que desees
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalDelete}
            handleButton2Click={handleUpdate}
            closeModalEdit={closeModalDelete}
        />
    )
}






