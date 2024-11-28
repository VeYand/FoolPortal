import {message} from 'antd'
import {useCallback, useEffect, useMemo} from 'react'
import {useLazyCreateSubject, useLazyDeleteSubject, useLazyUpdateSubject} from 'shared/libs/query'
import {useAppSelector} from 'shared/redux'
import {EditableListWidget} from 'widgets/editableListWidget/EditableListWidget'
import {EditableItem} from 'widgets/editableListWidget/EditableRow'
import {useInitializeSubjects} from './libs/useInitializeSubjects'

const SubjectsList = () => {
	const [createSubject] = useLazyCreateSubject()
	const [updateSubject] = useLazyUpdateSubject()
	const [deleteSubject] = useLazyDeleteSubject()
	const {initialize} = useInitializeSubjects()
	const {subjects} = useAppSelector(state => state.subjectEntity)

	const items = useMemo(
		(): EditableItem[] =>
			subjects.map(subject => ({id: subject.subjectId, name: subject.name})),
		[subjects],
	)

	useEffect(initialize, [initialize])

	const refreshSubjects = useCallback(async (action: () => Promise<any>, successMessage: string) => {
		try {
			const data = await action()
			if (data.isSuccess) {
				initialize()
				message.success(successMessage)
			}
			else {
				throw new Error(data.error.data)
			}
		}
		catch (error) {
			message.error('Что-то пошло не так. Попробуйте ещё раз.')
		}
	}, [initialize])

	const handleSave = (newName: string, id?: string) => {
		if (id) {
			refreshSubjects(
				() => updateSubject({subjectId: id, name: newName}),
				'Предмет успешно обновлён',
			)
		}
		else {
			refreshSubjects(
				() => createSubject({name: newName}),
				'Предмет успешно добавлен',
			)
		}
	}

	const handleDelete = (id: string) => {
		refreshSubjects(
			() => deleteSubject({subjectId: id}),
			'Предмет успешно удалён',
		)
	}

	return (
		<div>
			<EditableListWidget
				title="Предметы"
				data={items}
				onSave={handleSave}
				onDelete={handleDelete}
			/>
		</div>
	)
}

export {
	SubjectsList,
}
