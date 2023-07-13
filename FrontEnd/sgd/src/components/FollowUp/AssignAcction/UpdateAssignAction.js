import { React, useEffect, useState,useContext } from 'react'
import { fetchJson } from '../../../hooks/useFetchJson';
import Modal from '../../Modal/Modal';
import Select from 'react-select';
import { AuthContext } from '../../Context/AuthContext';
import { Notify } from '../../Modal/Notify';


export const UpdateAssignAction= ({ selectedDataRow, closeModalEdit }) => {

    const { loginData } = useContext(AuthContext);
    const [selectedDataAction, setSelectedDataAction] = useState({ value: selectedDataRow.action_id, label: selectedDataRow.action_name });
    const [action, setAction] = useState([]);

    const [data, setData] = useState({
        _actionOld: selectedDataRow.action_id,
        _articleOld: selectedDataRow.article_id,
        _actionNew: selectedDataRow.action_id,
        _articleNew: selectedDataRow.article_id
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

    const handleUpdate = async () => {

        if (data._actionOld === '' || data._articleOld === '' || data._actionNew === '' || data._articleNew === '') {
            handleShowError();
            return;
        }

        try {
            const requestData = {
                _actionOld: selectedDataRow.action_id,
                _articleOld: selectedDataRow.article_id,
                _actionNew: data._actionNew,
                _articleNew: data._articleNew
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiArticleAction/apiArticleAction.php/updateArticleAction',
                requestData
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalEdit();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const optionsAction = action.map((data) => ({
        value: data.action_id,
        label: data.action_name,
    }));

    const handleChangeaction = (event) => {
        setSelectedDataAction(event || '');
        if (event && event.value) {
            setData({ ...data, _actionNew: event.value });
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
            title={`Modificar acción de artículo: ${selectedDataRow.article_id}`}
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos de asignación de acción</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form>
                            <div className="row">
                                <div className="form-group col-sm-12">
                                    <label>Proceso</label>
                                    <Select
                                        onChange={handleChangeaction}
                                        options={optionsAction}
                                        placeholder={'Seleccione un proceso'}
                                        defaultValue={selectedDataAction}
                                    ></Select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            }
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalEdit}
            handleButton2Click={handleUpdate}
            closeModalEdit={closeModalEdit}
        />
    )
}






