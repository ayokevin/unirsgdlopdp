import { React, useEffect, useState, useContext } from 'react'
import { fetchJson } from '../../../hooks/useFetchJson';
import Modal from '../../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../../Context/AuthContext';
import { Notify } from '../../Modal/Notify';


export const InsertAssignAction = ({selectedDataRow, closeModalInsert }) => {

    const { loginData } = useContext(AuthContext);
    const [selectedDataAction, setSelectedDataAction] = useState(null);
    const [action, setAction] = useState([]);

    const [data, setData] = useState({
        _process: selectedDataRow.process_id,
        _action: ""
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataAction = async () => {
        try {
            const requestData = {
                idClient: loginData.clientId,
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiAction/apiAction.php/listActionReference',
                requestData
            );

            setAction(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        fetchDataAction();
    }, []);

    const handleInsert = async () => {

        if (data._action === '' ) {
            handleShowError();
            return;
        }

        try {
            const requestData = {
                _process: data._process,
                _action: data._action
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiActionProcess/apiActionProcess.php/insertActionProcess',
                requestData
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalInsert();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const optionsAction = action.map((data) => ({
        value: data.action_id,
        label: data.action_name,
    }));

    const handleChangeAction = (event) => {
        setSelectedDataAction(event || '');
        if (event && event.value) {
            setData({ ...data, _action: event.value });
        }
    };

    const handleShowError = () => {
        setNotifyError(true);
        setTimeout(() => {
            setNotifyError(false);
        }, 5000);
    };

    const handleShowSuccess = () => {
        setNotifySuccess(true);
        setTimeout(() => {
            setNotifySuccess(false);
        }, 2000);
    };

    return (
        <Modal
            title={`Asignar accion a proceso: ${selectedDataRow.process_name}`} 
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos de asignación de proceso</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form>
                            <div className="row">

                                <div className="form-group col-sm-12">
                                    <label>Acción</label>
                                    <Select
                                        onChange={handleChangeAction}
                                        options={optionsAction}
                                        placeholder={'Seleccione un articulo'}
                                        defaultValue={selectedDataAction}
                                        isClearable
                                    ></Select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            } // Aquí puedes pasar el contenido que desees
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalInsert}
            handleButton2Click={handleInsert}
            closeModalInsert={closeModalInsert}
        />
    )
}






