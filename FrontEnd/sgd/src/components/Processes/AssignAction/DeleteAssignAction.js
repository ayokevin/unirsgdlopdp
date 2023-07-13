import { React,} from 'react'
import { fetchJson } from '../../../hooks/useFetchJson';
import Modal from '../../Modal/Modal';


export const DeleteAssignAction = ({ selectedDataRow, closeModalDelete }) => {


    const handleUpdate = async () => {
        try {
            const requestData = {
                _process: selectedDataRow.process_id,
                _action: selectedDataRow.action_id
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiActionProcess/apiActionProcess.php/deleteActionProcess',
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
            title={`Eliminar acción del proceso: ${selectedDataRow.process_name}`}
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Eliminacion de asignacion de acción</h3>
                    </div>
                    <div className="card-body ">
                        <div className="card-body" style={{ display: 'block' }}>
                            Esta seguro que desea borrar la asignacion de:<br /><br />
                            <strong>Acción:</strong> {selectedDataRow.action_name}<br />
                            <strong>Proceso:</strong> {selectedDataRow.process_name}
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






