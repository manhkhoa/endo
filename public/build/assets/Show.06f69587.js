import{u as D,k as C,m as P,s as V,r as s,o as p,c as Q,d as t,j as e,e as u,F as T,h as f,v as o,x as i,p as j,i as y,a as A}from"./app.ca5d1c04.js";const H={class:"grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2"},I={name:"EmployeeQualificationShow"},O=Object.assign(I,{props:{employee:{type:Object,default(){return{}}}},setup(r){D();const d=C(),c=P(),b={},_="employee/qualification/",l=V({...b}),g=a=>{Object.assign(l,a)};return(a,m)=>{const $=s("PageHeaderAction"),B=s("PageHeader"),n=s("BaseDataView"),q=s("ListMedia"),h=s("BaseButton"),v=s("ShowButton"),w=s("BaseCard"),S=s("ShowItem"),E=s("ParentTransition");return p(),Q(T,null,[t(B,{title:a.$trans(u(d).meta.trans,{attribute:a.$trans(u(d).meta.label)}),navs:[{label:a.$trans("employee.employee"),path:"Employee"},{label:r.employee.contact.name,path:{name:"EmployeeShow",params:{uuid:r.employee.uuid}}}]},{default:e(()=>[t($,{name:"EmployeeQualification",title:a.$trans("employee.qualification.qualification"),actions:["list"]},null,8,["title"])]),_:1},8,["title","navs"]),t(E,{appear:"",visibility:!0},{default:e(()=>[t(S,{"init-url":_,uuid:u(d).params.uuid,"module-uuid":u(d).params.muuid,onSetItem:g,onRedirectTo:m[1]||(m[1]=k=>u(c).push({name:"EmployeeQualification",params:{uuid:r.employee.uuid}}))},{default:e(()=>[l.uuid?(p(),f(w,{key:0},{title:e(()=>[o(i(l.level.name),1)]),footer:e(()=>[t(v,null,{default:e(()=>[u(j)("employee:edit")?(p(),f(h,{key:0,design:"primary",onClick:m[0]||(m[0]=k=>u(c).push({name:"EmployeeQualificationEdit",params:{uuid:r.employee.uuid,muuid:l.uuid}}))},{default:e(()=>[o(i(a.$trans("general.edit")),1)]),_:1})):y("",!0)]),_:1})]),default:e(()=>[A("dl",H,[t(n,{label:a.$trans("employee.qualification.props.course")},{default:e(()=>[o(i(l.course),1)]),_:1},8,["label"]),t(n,{label:a.$trans("employee.qualification.props.institute")},{default:e(()=>[o(i(l.institute),1)]),_:1},8,["label"]),t(n,{label:a.$trans("employee.qualification.props.affiliated_to")},{default:e(()=>[o(i(l.affiliatedTo),1)]),_:1},8,["label"]),t(n,{label:a.$trans("employee.qualification.props.result")},{default:e(()=>[o(i(l.result),1)]),_:1},8,["label"]),t(n,{label:a.$trans("employee.qualification.props.start_date")},{default:e(()=>[o(i(l.startDateDisplay),1)]),_:1},8,["label"]),t(n,{label:a.$trans("employee.qualification.props.end_date")},{default:e(()=>[o(i(l.endDateDisplay),1)]),_:1},8,["label"]),t(n,{class:"col-span-1 sm:col-span-2"},{default:e(()=>[t(q,{media:l.media,url:`/app/employees/${r.employee.uuid}/qualifications/${l.uuid}/`},null,8,["media","url"])]),_:1}),t(n,{label:a.$trans("general.created_at")},{default:e(()=>[o(i(l.createdAt),1)]),_:1},8,["label"]),t(n,{label:a.$trans("general.updated_at")},{default:e(()=>[o(i(l.updatedAt),1)]),_:1},8,["label"])])]),_:1})):y("",!0)]),_:1},8,["uuid","module-uuid"])]),_:1})],64)}}});export{O as default};
