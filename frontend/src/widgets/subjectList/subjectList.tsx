import {useEffect, useMemo} from 'react'
import {useLazyCreateSubject} from 'shared/libs/query/useLazyCreateSubject'
import {useLazyDeleteSubject} from 'shared/libs/query/useLazyDeleteSubject'
import {useLazyUpdateSubject} from 'shared/libs/query/useLazyUpdateSubject'
import {useAppSelector} from 'shared/redux'
import {EditableListWidget} from 'widgets/editableListWidget/EditableListWidget'
import {EditableItem} from 'widgets/editableListWidget/EditableRow'
import {useInitializeSubjects} from './libs/useInitializeSubjects'

const SubjectList = () => {
	const [createSubject] = useLazyCreateSubject()
	const [updateSubject] = useLazyUpdateSubject()
	const [deleteSubject] = useLazyDeleteSubject()
	const {initialize} = useInitializeSubjects()
	const {subjects} = useAppSelector(state => state.subjectEntity)
	const items = useMemo((): EditableItem[] => subjects.map(
		subject => ({id: subject.subjectId, name: subject.name}),
	), [subjects])

	useEffect(initialize, [initialize])

	const handleSave = async (newName: string, id?: string) => {
		if (id) {
			const data = await updateSubject({subjectId: id, name: newName})

			if (data.isSuccess) {
				initialize()
			}
			else {
				console.log('Не удалось обновить')// TODO добавить тостики
			}
		}
		else {
			const data = await createSubject({name: newName})

			if (data.isSuccess) {
				initialize()
			}
			else {
				console.log('Не удалось сохранить')
			}
		}
	}

	const handleDelete = async (id: string) => {
		const data = await deleteSubject({subjectId: id})

		if (data.isSuccess) {
			initialize()
		}
		else {
			console.log('Не удалось удалить')// TODO добавить тостики
		}
	}

	return (
		<div>
			<EditableListWidget title="Предметы" data={items} onSave={handleSave} onDelete={handleDelete} />
		</div>
	)
}

export {
	SubjectList,
}
